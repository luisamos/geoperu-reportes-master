<?php
@session_start();
	include ('../conexion_postgres.phtml');
	$linkPG = Conectarse_POSTGRES();
	echo('<h3><p align="center">CENTRO DE EJECUCI&Oacute;N DE ACTUALIZACI&Oacute;N DE TABLAS</p></h3><p align="center">--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</p>');
	include ('crom_ppss_beneficiarios.php');
	include ('crom_data_mef_funciones.php');
	include ('crom_peru_info_gl.php');
	//include ('crom_presupuesto_municipal.php');// este crom se corre en modo local, para ello actualizar tablas mef
	echo('<br><br><p align="center">--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</p>');
pg_close($linkPG);
?>