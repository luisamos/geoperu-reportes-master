<?php
/**
 * Redirector GeoPerú: rutas del proyecto PHP legado -> /censos/{anio}/{codigo}
 *
 * Capas priorizadas (nivel -> longitud del código):
 *   departamento     2   (ubigeo)             08
 *   provincia        4   (ubigeo)             0801
 *   distrito         6   (ubigeo)             080101
 *   centro_poblado  10   (ubigeo + cod_ccpp)  0801010001
 */
declare(strict_types=1);

require __DIR__ . '/../src/Router.php';

$router = new CensoRouter(
    rtrim(getenv('CENSO_BASE_URL') ?: 'https://reportes.geoperu.gob.pe/censos', '/'),
    getenv('CENSO_YEAR') ?: '2025',
    (int) (getenv('REDIRECT_CODE') ?: 302)
);

$path   = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$params = $_GET;

if ($path === '/health') {
    header('Content-Type: text/plain');
    echo 'ok';
    exit;
}

if ($path === '/routes') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($router->catalog(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

$target = $router->resolve($path, $params);

if ($target === null) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Ruta no soportada. Capas priorizadas: departamento, provincia, distrito, centro poblado.\n";
    echo "Consulte /routes para ver el catálogo.\n";
    exit;
}

header('Location: ' . $target, true, $router->redirectCode());
exit;
