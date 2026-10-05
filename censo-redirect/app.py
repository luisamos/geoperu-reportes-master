"""Redirector GeoPerú (Flask): rutas del proyecto PHP legado -> /censos/{anio}/{codigo}

Niveles priorizados (longitud del código):
  departamento 2 · provincia 4 · distrito 6 · centro_poblado 10 (ubigeo + cod_ccpp)
"""
import os
import re

from flask import Flask, jsonify, redirect, request

BASE_URL = os.getenv("CENSO_BASE_URL", "https://reportes.geoperu.gob.pe/censos").rstrip("/")
YEAR = os.getenv("CENSO_YEAR", "2025")
REDIRECT_CODE = int(os.getenv("REDIRECT_CODE", "302"))
if REDIRECT_CODE not in (301, 302, 307, 308):
    REDIRECT_CODE = 302

LEVELS = {
    "departamento":   {"len": 2,  "alias": "departamento",   "legacy": "consulta_Departamento", "olayer": "peru_departamentos", "ocampo": "cod_dpto"},
    "provincia":      {"len": 4,  "alias": "provincia",      "legacy": "consulta_Provincia",    "olayer": "peru_provincias",    "ocampo": "cod_prov"},
    "distrito":       {"len": 6,  "alias": "distrito",       "legacy": "consulta_Distrito",     "olayer": "peru_distritos",     "ocampo": "cod_dist"},
    "centro_poblado": {"len": 10, "alias": "centro-poblado", "legacy": "consulta_ccpp",         "olayer": "peru_ccpp",          "ocampo": "cod_ccpp"},
}
ALIASES = {
    "departamento": "departamento", "dpto": "departamento",
    "provincia": "provincia", "prov": "provincia",
    "distrito": "distrito", "dist": "distrito",
    "centro-poblado": "centro_poblado", "centro_poblado": "centro_poblado", "ccpp": "centro_poblado",
}
LEGACY = {cfg["legacy"].lower(): level for level, cfg in LEVELS.items()}

app = Flask(__name__)


def build(level, code, year=None, pad=False):
    """URL destino o None si el código no corresponde al nivel.

    pad=True completa ceros a la izquierda (enlaces guardados con ovalor=8 -> 08).
    """
    code = re.sub(r"\D", "", code or "")
    if pad and level in LEVELS and code:
        code = code.zfill(LEVELS[level]["len"])
    if level is None:
        level = next((n for n, c in LEVELS.items() if c["len"] == len(code)), None)
    if level is None or LEVELS[level]["len"] != len(code):
        return None
    year = year if year and re.fullmatch(r"\d{4}", year) else YEAR
    return f"{BASE_URL}/{year}/{code}"


def go(url):
    if url is None:
        return (
            "Ruta no soportada. Capas priorizadas: departamento, provincia, distrito, centro poblado.\n"
            "Consulte /routes para ver el catálogo.\n",
            404,
            {"Content-Type": "text/plain; charset=utf-8"},
        )
    return redirect(url, code=REDIRECT_CODE)


@app.get("/health")
def health():
    return "ok"


@app.get("/routes")
def routes():
    return jsonify([
        {
            "nivel": n,
            "longitud": c["len"],
            "ruta_nueva": f"/{c['alias']}/{{codigo}}",
            "ruta_legada": f"/{c['legacy']}.phtml?olayer={c['olayer']}&ocampo={c['ocampo']}&ovalor={{codigo}}",
            "ejemplo": build(n, "08".ljust(c["len"], "1")),
        }
        for n, c in LEVELS.items()
    ])


# Rutas legadas: /consulta_Distrito.phtml?olayer=..&ocampo=..&ovalor=080101
@app.get("/<name>.<ext>")
def legacy(name, ext):
    # Enlaces guardados: consulta_Departamento.phtml?olayer=..&ocampo=cod_dpto&ovalor=25
    # (olayer/ocampo se ignoran: el nivel lo define la plantilla y el código es ovalor)
    if ext.lower() not in ("phtml", "php") or name.lower() not in LEGACY:
        return go(None)
    return go(build(LEGACY[name.lower()], request.args.get("ovalor", ""), request.args.get("anio"), pad=True))


# /distrito/080101
@app.get("/<level>/<code>")
def by_level(level, code):
    if re.fullmatch(r"\d{4}", level):  # /2024/080101 (nivel por longitud)
        return go(build(None, code, level))
    if level not in ALIASES:
        return go(None)
    return go(build(ALIASES[level], code, request.args.get("anio")))


# /censos/2024/080101 (nivel por longitud)

@app.get("/censos/<year>/<code>")
def by_censos(year, code):
    return go(build(None, code, year))


# /080101 (nivel por longitud)
@app.get("/<code>")
def by_code(code):
    return go(build(None, code, request.args.get("anio")))


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=8000)
