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

## 4. Docker y despliegue en desarrollo

Requisitos: Docker 24+ con Docker Compose v2.

### Opción A – Docker Compose (recomendada)

```bash
docker compose up --build -d     # construye la imagen y levanta el servicio
docker compose ps                # estado (debe aparecer "healthy")
docker compose logs -f           # ver registros
docker compose down              # detener y eliminar el contenedor
```

El servicio queda en **http://localhost:8080** (puerto 8080 del equipo → 8000 del contenedor).
Para cambiar año, dominio o tipo de redirect, edite la sección `environment` de
`docker-compose.yml` y ejecute de nuevo `docker compose up -d`.

### Opción B – Docker a mano

```bash
docker build -t censo-redirect:dev .

docker run -d --name censo-redirect -p 8080:8000 \
  -e CENSO_YEAR=2025 \
  -e REDIRECT_CODE=302 \
  censo-redirect:dev

docker logs -f censo-redirect
docker rm -f censo-redirect      # detener y eliminar
```

### Comprobar el despliegue

```bash
curl localhost:8080/health                      # ok
curl localhost:8080/routes                      # catálogo JSON
curl -I "localhost:8080/consulta_Distrito.phtml?ovalor=080101"
# Location: https://reportes.geoperu.gob.pe/censos/2025/080101
```

### Detalles de la imagen

- Base `python:3.12-slim`; ejecuta con **gunicorn** (2 workers) en el puerto 8000.
- Corre con un usuario sin privilegios y tiene `HEALTHCHECK` sobre `/health`.
- Para publicarla en un registro propio:
  `docker tag censo-redirect:dev <registro>/censo-redirect:<versión>` y `docker push …`.

### Que los enlaces antiguos lleguen al servicio

Los enlaces viejos apuntan al dominio donde vivía el sistema PHP. Para que lleguen aquí, ese
dominio (o su proxy inverso: Nginx, Apache, balanceador) debe enviar el tráfico al contenedor.
Ejemplo con Nginx:

```nginx
location / {
    proxy_pass http://127.0.0.1:8080;
}
```

Tras validar en desarrollo, cambie `REDIRECT_CODE` a `301` para producción.

---

## 5. Estructura del proyecto

```
app.py              Aplicación Flask (toda la lógica de rutas)
test_app.py         Pruebas automáticas (pytest)
requirements.txt    Dependencias (Flask, gunicorn)
Dockerfile          Imagen del servicio
docker-compose.yml  Despliegue en desarrollo
```

El código PHP original ya no está en esta rama; sigue disponible en `main` y en el historial de git.

## 6. Agregar otro nivel o capa

En `app.py`, agregue una entrada al diccionario `LEVELS` (longitud del código, nombre de la
plantilla antigua) y, si quiere una ruta amigable, un alias en `ALIASES`. Luego añada sus casos
a `test_app.py` y ejecute `python -m pytest -q`.
