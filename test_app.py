import pytest

from app import app


@pytest.fixture
def client():
    return app.test_client()


@pytest.mark.parametrize("url,dest", [
    ("/consulta_Departamento.phtml?olayer=peru_departamento&ocampo=cod_dpto&ovalor=25", "/2025/25"),
    ("/consulta_departamento.phtml?ovalor=8", "/2025/08"),
    ("/consulta_Provincia.phtml?olayer=peru_provincia&ocampo=cod_prov&ovalor=0801", "/2025/0801"),
    ("/consulta_Distrito.phtml?ovalor=080101", "/2025/080101"),
    ("/consulta_ccpp.phtml?ovalor=0801010001", "/2025/0801010001"),
    ("/distrito/080101", "/2025/080101"),
    ("/2024/080101", "/2024/080101"),
    ("/reportes/consulta_Departamento.phtml?olayer=peru_departamento&ocampo=cod_dpto&ovalor=16", "/2025/16"),
    ("/reportes/distrito/080101", "/2025/080101"),
])
def test_redirects(client, url, dest):
    r = client.get(url)
    assert r.status_code == 302
    assert r.headers["Location"] == "https://reportes.geoperu.gob.pe/censos" + dest


@pytest.mark.parametrize("url", [
    "/consulta_Departamento.phtml", "/consulta_Departamento.phtml?ovalor=123",
    "/consulta_pobreza_distritos.phtml?ovalor=1", "/distrito/08",
])
def test_not_found(client, url):
    assert client.get(url).status_code == 404


def test_health_with_prefix(client):
    assert client.get("/reportes/health").data == b"ok"
