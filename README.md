# GeoPerú Reportes – Redirector de censos

Servicio web (Python/Flask) que recibe los **enlaces antiguos** del sistema de reportes PHP
(por ejemplo `consulta_Departamento.phtml?...&ovalor=25`) y **redirige** al reporte actual:

```
https://reportes.geoperu.gob.pe/censos/{año}/{código}
```

No usa base de datos ni guarda estado: solo traduce la URL y responde un redirect (HTTP 302).

---

## 1. ¿Cómo funciona?

El sistema anterior tenía una plantilla PHP por capa y recibía el código geográfico en el
parámetro `ovalor`. El redirector prioriza las cuatro capas territoriales:

| Nivel | Plantilla antigua | Código | Ejemplo | Destino |
|---|---|---|---|---|
| Departamento | `consulta_Departamento.phtml` | 2 dígitos | `25` | `/censos/2025/25` |
| Provincia | `consulta_Provincia.phtml` | 4 dígitos | `0801` | `/censos/2025/0801` |
| Distrito | `consulta_Distrito.phtml` | 6 dígitos (ubigeo) | `080101` | `/censos/2025/080101` |
| Centro poblado | `consulta_ccpp.phtml` | 10 dígitos (ubigeo + código ccpp) | `0801010001` | `/censos/2025/0801010001` |

**Ejemplo de enlace guardado:**

```
/consulta_Departamento.phtml?olayer=peru_departamento&ocampo=cod_dpto&ovalor=25
   └─► 302  https://reportes.geoperu.gob.pe/censos/2025/25
```

Reglas a tener en cuenta:

- Solo se lee `ovalor`; `olayer` y `ocampo` se ignoran (el nivel lo define la plantilla).
- Si el código viene sin ceros (`ovalor=8`), se completa (`08`).
- El nombre de la plantilla no distingue mayúsculas (`consulta_departamento.phtml` también sirve).
- Un código con longitud incorrecta para su nivel responde **404**, no un destino equivocado.
- Las variantes históricas (`*_2007.phtml`) y las ~270 plantillas temáticas (pobreza, NBI, OEFA,
  etc.) **no** se redirigen: responden 404.

### Rutas disponibles

| Ruta | Qué hace |
|---|---|
| `/consulta_{Departamento,Provincia,Distrito,ccpp}.phtml?ovalor=…` | Enlaces antiguos |
| `/departamento/08`, `/provincia/0801`, `/distrito/080101`, `/centro-poblado/0801010001` | Rutas nuevas por nivel |
| `/{código}` | Nivel según la longitud del código |
| `/{año}/{código}` y `/censos/{año}/{código}` | Igual, con año explícito |
| `?anio=2024` | Cambia el año en cualquiera de las rutas anteriores |
| `/routes` | Catálogo en JSON |
| `/health` | Responde `ok` (para monitoreo) |

> **Pendiente de confirmar:** que el dominio destino use el mismo patrón
> (`/censos/{año}/{código}`) para provincia, distrito y centro poblado, y no solo para departamento.

---

## 2. Configuración

Se hace con variables de entorno:

| Variable | Por defecto | Descripción |
|---|---|---|
| `CENSO_BASE_URL` | `https://reportes.geoperu.gob.pe/censos` | Dominio y ruta base del destino |
| `CENSO_YEAR` | `2025` | Año del censo por defecto |
| `URL_PREFIX` | `/reportes` | Carpeta con la que se publica el servicio (`visor.geoperu.gob.pe/reportes/...`); se ignora si el proxy ya la quita. Vacío = sin prefijo |
| `REDIRECT_CODE` | `302` | `302` mientras se prueba; `301` cuando la migración sea definitiva (301/302/307/308) |

---

## 3. Probar en local (sin Docker)

Requisitos: Python 3.12+.

```bash
python3 -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt pytest

python app.py                 # servidor de desarrollo en http://localhost:8000
python -m pytest -q           # pruebas automáticas
```

Verificar:

```bash
curl -I "http://localhost:8000/consulta_Departamento.phtml?olayer=peru_departamento&ocampo=cod_dpto&ovalor=25"
# HTTP/1.1 302 FOUND
# Location: https://reportes.geoperu.gob.pe/censos/2025/25
```

---

## 4. Docker: imagen y despliegue (desarrollo y producción)

El contenedor **escucha en el puerto 80** y se publica también en el puerto 80 del servidor,
tanto en desarrollo como en producción. Reemplace `DDMMAAAA-HHMM` por la fecha y hora de la
versión (ejemplo: `05102026-1030`).

**1. Token y acceso a GitHub**

Use un *Personal Access Token* de GitHub con permisos `write:packages` (y `read:packages` para
descargar). No lo escriba en archivos del repositorio.

```bash
echo xxx-xxx-xxx-xxx-xxx | docker login ghcr.io -u luisamos --password-stdin
```

> `-u` es el usuario de GitHub (`luisamos`). Si su registro le pide el correo, use
> `luisamos7@gmail.com`.

**2. Generar la imagen** (desde la raíz del proyecto, donde está el `Dockerfile`)

```bash
docker build --no-cache -t censo-redirect-DDMMAAAA-HHMM -f Dockerfile .
```

**3. Etiquetar la imagen con la URL de GitHub Packages** (el nombre debe ir en minúsculas)

```bash
docker tag censo-redirect-DDMMAAAA-HHMM ghcr.io/luisamos/censo-redirect-DDMMAAAA-HHMM
```

**4. Subir la imagen a GitHub Packages**

```bash
docker push ghcr.io/luisamos/censo-redirect-DDMMAAAA-HHMM
```

**5. Levantarla en un entorno** (puerto 80)

```bash
docker run -d -p 80:80 --name censo-redirect \
  -e CENSO_YEAR=2025 -e REDIRECT_CODE=302 \
  ghcr.io/luisamos/censo-redirect-DDMMAAAA-HHMM
```

Quedará en `http://<servidor>` (puerto 80). En un servidor distinto al que construyó la imagen,
haga antes el paso 1 (`docker login`) si el paquete es privado. Para actualizar, ejecute
`docker rm -f censo-redirect` y vuelva a correr el paso 5 con la nueva versión. El puerto 80
del servidor debe estar libre.

**Comprobar**

```bash
curl localhost/health            # ok
curl -I "localhost/consulta_Distrito.phtml?ovalor=080101"
# Location: https://reportes.geoperu.gob.pe/censos/2025/080101
```

**Que los enlaces antiguos lleguen al servicio:** el dominio donde vivía el sistema PHP debe
apuntar (DNS o proxy inverso) al servidor donde corre este contenedor. Tras validar en
desarrollo, use `-e REDIRECT_CODE=301` en producción.

**Publicado en una carpeta (`https://visor.geoperu.gob.pe/reportes/...`)**

El servicio acepta la ruta con o sin el prefijo `/reportes` (variable `URL_PREFIX`). El proxy del
servidor (Nginx, Apache o el túnel/Cloudflare) debe enviar `/reportes/` al contenedor, por ejemplo:

```nginx
location /reportes/ {
    proxy_pass http://127.0.0.1:80/;     # el contenedor publicado en el puerto 80
}
```

**Error 502 (Bad gateway)** significa que el proxy no logra hablar con el contenedor. Revise en el
servidor, en este orden:

```bash
docker ps -a                        # ¿está "Up"? si salió, ver el motivo:
docker logs censo-redirect          # errores al arrancar
curl -i localhost/health            # desde el servidor: debe responder "ok"
curl -I "localhost/reportes/consulta_Departamento.phtml?ovalor=16"   # debe dar 302
```

Causas habituales: contenedor detenido, el puerto 80 ya ocupado por otro servicio (el contenedor no
arranca), o el `proxy_pass` apuntando a un puerto distinto de donde se publicó el contenedor.

**Si corre en Kubernetes (pod `prod-geoperu-reportes-…`)**

Si el log del pod muestra `Listening at: http://0.0.0.0:80` y los workers arrancan, el contenedor
está bien y el 502 está entre el Ingress/Service y el pod. Verifique:

- El **Service** debe tener `targetPort: 80` (no el puerto del sistema PHP anterior).
- El **Ingress** debe enviar `/reportes` al Service de este pod. El servicio acepta la ruta con
  o sin el prefijo, así que no hace falta reescribirla.
- Las sondas *readiness/liveness* (si existen) deben apuntar a `GET /health` en el puerto 80.
- Con la imagen nueva, cada petición queda en el log del pod (`--access-logfile -`): si al abrir
  el enlace no aparece ninguna línea, la petición no está llegando al pod.

---

## 5. Estructura del proyecto

```
app.py              Aplicación Flask (toda la lógica de rutas)
test_app.py         Pruebas automáticas (pytest)
requirements.txt    Dependencias (Flask, gunicorn)
Dockerfile          Imagen del servicio (puerto 80)
```

El código PHP original ya no está en esta rama; sigue disponible en `main` y en el historial de git.

## 6. Agregar otro nivel o capa

En `app.py`, agregue una entrada al diccionario `LEVELS` (longitud del código, nombre de la
plantilla antigua) y, si quiere una ruta amigable, un alias en `ALIASES`. Luego añada sus casos
a `test_app.py` y ejecute `python -m pytest -q`.
