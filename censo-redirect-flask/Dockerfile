FROM python:3.12-slim

WORKDIR /app
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt
COPY app.py .

ENV CENSO_BASE_URL=https://reportes.geoperu.gob.pe/censos \
    CENSO_YEAR=2025 \
    REDIRECT_CODE=302

RUN useradd -r app
USER app
EXPOSE 8000
HEALTHCHECK --interval=30s --timeout=3s CMD python -c "import urllib.request as u; u.urlopen('http://localhost:8000/health')" || exit 1
CMD ["gunicorn", "-b", "0.0.0.0:8000", "-w", "2", "app:app"]
