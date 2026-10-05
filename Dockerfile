FROM python:3.12-slim

# libcap2-bin: permite que un usuario sin privilegios use el puerto 80
RUN apt-get update \
 && apt-get install -y --no-install-recommends libcap2-bin \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /app
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt
COPY app.py .

RUN setcap 'cap_net_bind_service=+ep' "$(readlink -f "$(command -v python)")" \
 && useradd -r app

ENV CENSO_BASE_URL=https://reportes.geoperu.gob.pe/censos \
    CENSO_YEAR=2025 \
    REDIRECT_CODE=302

USER app
EXPOSE 80
HEALTHCHECK --interval=30s --timeout=3s CMD python -c "import urllib.request as u; u.urlopen('http://localhost:80/health')" || exit 1
CMD ["gunicorn", "-b", "0.0.0.0:80", "-w", "2", "app:app"]
