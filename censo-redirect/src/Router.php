<?php
declare(strict_types=1);

final class CensoRouter
{
    /** nivel => [longitud del código, plantilla legada, parámetros legados olayer/ocampo] */
    private const LEVELS = [
        'departamento'   => ['len' => 2,  'legacy' => 'consulta_Departamento', 'olayer' => 'peru_departamentos', 'ocampo' => 'cod_dpto'],
        'provincia'      => ['len' => 4,  'legacy' => 'consulta_Provincia',    'olayer' => 'peru_provincias',    'ocampo' => 'cod_prov'],
        'distrito'       => ['len' => 6,  'legacy' => 'consulta_Distrito',     'olayer' => 'peru_distritos',     'ocampo' => 'cod_dist'],
        'centro_poblado' => ['len' => 10, 'legacy' => 'consulta_ccpp',         'olayer' => 'peru_ccpp',          'ocampo' => 'cod_ccpp'],
    ];

    /** alias de URL amigable -> nivel */
    private const ALIASES = [
        'departamento' => 'departamento', 'dpto' => 'departamento',
        'provincia' => 'provincia', 'prov' => 'provincia',
        'distrito' => 'distrito', 'dist' => 'distrito',
        'centro-poblado' => 'centro_poblado', 'centro_poblado' => 'centro_poblado', 'ccpp' => 'centro_poblado',
    ];

    public function __construct(
        private string $baseUrl,
        private string $year,
        private int $redirectCode = 302
    ) {
        if (!in_array($this->redirectCode, [301, 302, 307, 308], true)) {
            $this->redirectCode = 302;
        }
    }

    public function redirectCode(): int
    {
        return $this->redirectCode;
    }

    /** Devuelve la URL destino o null si la ruta no está soportada. */
    public function resolve(string $path, array $query): ?string
    {
        $segments = array_values(array_filter(explode('/', trim($path, '/')), 'strlen'));

        // 1) Rutas legadas: /consulta_Distrito.phtml?olayer=..&ovalor=080101
        if (count($segments) === 1 && preg_match('/^(consulta_[A-Za-z0-9_]+)\.(phtml|php)$/', $segments[0], $m)) {
            foreach (self::LEVELS as $level => $cfg) {
                if ($cfg['legacy'] === $m[1]) {
                    return $this->build($level, (string) ($query['ovalor'] ?? ''), $query['anio'] ?? null);
                }
            }
            return null;
        }

        // 2) /{nivel}/{codigo}            ej. /distrito/080101
        if (count($segments) === 2 && isset(self::ALIASES[strtolower($segments[0])])) {
            return $this->build(self::ALIASES[strtolower($segments[0])], $segments[1], $query['anio'] ?? null);
        }

        // 3) /censos/{anio}/{codigo} y /{anio}/{codigo} (nivel por longitud)
        if (count($segments) === 3 && $segments[0] === 'censos') {
            array_shift($segments);
        }
        if (count($segments) === 2 && preg_match('/^\d{4}$/', $segments[0])) {
            return $this->build(null, $segments[1], $segments[0]);
        }

        // 4) /{codigo}                    nivel por longitud
        if (count($segments) === 1) {
            return $this->build(null, $segments[0], $query['anio'] ?? null);
        }

        return null;
    }

    /** @param ?string $level null = inferir por longitud */
    private function build(?string $level, string $code, mixed $year): ?string
    {
        $code = preg_replace('/\D/', '', $code) ?? '';
        $len  = strlen($code);

        if ($level === null) {
            foreach (self::LEVELS as $name => $cfg) {
                if ($cfg['len'] === $len) {
                    $level = $name;
                    break;
                }
            }
        }
        if ($level === null || !isset(self::LEVELS[$level]) || self::LEVELS[$level]['len'] !== $len) {
            return null;
        }

        $year = (is_string($year) || is_int($year)) && preg_match('/^\d{4}$/', (string) $year)
            ? (string) $year
            : $this->year;

        return "{$this->baseUrl}/{$year}/{$code}";
    }

    public function catalog(): array
    {
        $out = [];
        foreach (self::LEVELS as $level => $cfg) {
            $example = str_pad('08', $cfg['len'], '01');
            $out[] = [
                'nivel'        => $level,
                'longitud'     => $cfg['len'],
                'ruta_nueva'   => '/' . array_search($level, self::ALIASES, true) . '/{codigo}',
                'ruta_legada'  => "/{$cfg['legacy']}.phtml?olayer={$cfg['olayer']}&ocampo={$cfg['ocampo']}&ovalor={codigo}",
                'ejemplo'      => $this->build($level, $example, null),
            ];
        }
        return $out;
    }
}
