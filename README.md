# censo-redirect

Redirector Flask (gunicorn) que lleva las rutas legadas de reportes a
`https://reportes.geoperu.gob.pe/censos/{año}/{codigo}`. Diagnóstico en [DIAGNOSTICO.md](DIAGNOSTICO.md).

```bash
docker compose up --build
curl -I localhost:8080/distrito/080101
# 302 -> https://reportes.geoperu.gob.pe/censos/2025/080101
curl -I "localhost:8080/consulta_ccpp.phtml?ovalor=0801010001"
curl localhost:8080/routes
```

Rutas: `/departamento/08`, `/provincia/0801`, `/distrito/080101`, `/centro-poblado/0801010001`,
`/{codigo}`, `/{año}/{codigo}`, `/censos/{año}/{codigo}`, legadas `consulta_*.phtml`, `/routes`, `/health`.
Variables: `CENSO_BASE_URL`, `CENSO_YEAR` (2025), `REDIRECT_CODE` (302).
