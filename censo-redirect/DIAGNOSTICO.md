# Diagnóstico de rutas PHP disponibles

Proyecto legado (raíz del repo): ~650 archivos, **sin router central** (no hay `index.php`
ni `.htaccess`; `index.html` está vacío). Cada reporte es una plantilla suelta
`consulta_<capa>.phtml` que recibe parámetros por query string:

```
consulta_X.phtml?olayer=<capa>&ocampo=<campo>&ovalor=<codigo>
```

`ovalor` se sanea con `limpiarNumeros()` (solo dígitos) y se usa en consultas SQL a PostgreSQL
(`conexion_postgres.phtml`). Hay ~280 plantillas que leen `$_GET['ovalor']`.

## Rutas priorizadas (capas representativas)

| Nivel | Plantilla legada | Tabla consultada | Código (`ovalor`) | Destino nuevo |
|---|---|---|---|---|
| Departamento | `consulta_Departamento.phtml` | `data_vse_dpto_2017` (`cod_dpto`) | 2 dígitos | `/censos/{año}/08` |
| Provincia | `consulta_Provincia.phtml` | `data_vse_prov_2017` (`cod_prov`) | 4 dígitos | `/censos/{año}/0801` |
| Distrito | `consulta_Distrito.phtml` | `peru_distritos` (`cod_dist`) | 6 dígitos (ubigeo) | `/censos/{año}/080101` |
| Centro poblado | `consulta_ccpp.phtml` | `peru_ccpp` (`cod_ccpp`) | 10 dígitos (ubigeo + cod_ccpp) | `/censos/{año}/0801010001` |

Variantes históricas (`*_2007.phtml`) existen para los tres primeros niveles: **no se redirigen**
(son otro censo/año).

## Otras rutas detectadas (fuera de alcance por ahora)

- Temáticas por capa: `consulta_pobreza_*`, `consulta_nbi*`, `consulta_pma_*`, `consulta_cenec`,
  `consulta_cenagro_*`, `consulta_IDH*`, `consulta_vulnerabilidad_*`, `consulta_tumbes_*`,
  `consulta_oefa_*`, `consulta_adinelsa_*`, etc. (≈ 270 plantillas, puntos/polígonos puntuales por `gid`).
- Genéricas: `consulta_capa.phtml` (`olayer` + `ovalor`).
- PHP puro: `consulta_mosca_fruta.php`, `consulta_proy_mi_riego.php`, `consulta_registro.php`,
  `consulta_uso_may_tierras.php`, `dashboard.php`, `crom/*.php`, `graficos/*.php`.

## Router nuevo (Flask, este proyecto)

Rutas soportadas (el nivel se infiere por longitud del código si no se indica):

- `/departamento/08`, `/provincia/0801`, `/distrito/080101`, `/centro-poblado/0801010001`
- `/{codigo}` y `/{año}/{codigo}` y `/censos/{año}/{codigo}`
- Legadas: `/consulta_Distrito.phtml?olayer=..&ocampo=..&ovalor=080101`
- `/routes` (catálogo JSON) y `/health`

Un código con longitud inválida para el nivel responde 404. Año por defecto: `CENSO_YEAR` (2025);
`?anio=2024` lo sobreescribe.

> Nota: el destino `https://reportes.geoperu.gob.pe/censos/2025/08` se asumió con `08` = ubigeo de
> departamento; confirmar que provincia/distrito/ccpp usan el mismo patrón en el dominio destino.
