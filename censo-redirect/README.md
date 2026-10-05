# censo-redirect

Contenedor PHP/Apache que redirige las rutas legadas de reportes a
`https://reportes.geoperu.gob.pe/censos/{año}/{codigo}`. Ver [DIAGNOSTICO.md](DIAGNOSTICO.md).

```bash
docker compose up --build
curl -I localhost:8080/distrito/080101
# HTTP/1.1 302 ... Location: https://reportes.geoperu.gob.pe/censos/2025/080101
curl -I "localhost:8080/consulta_ccpp.phtml?olayer=peru_ccpp&ocampo=cod_ccpp&ovalor=0801010001"
```

Variables: `CENSO_BASE_URL`, `CENSO_YEAR` (2025), `REDIRECT_CODE` (302).
