<?php
include_once ('conexion_postgres.phtml');

function getMes($m) {
	switch ($m) {
		case '01': return 'Ene'; break;
		case '02': return 'Feb'; break;
		case '03': return 'Mar'; break;
		case '04': return 'Abr'; break;
		case '05': return 'May'; break;
		case '06': return 'Jun'; break;
		case '07': return 'Jul'; break;
		case '08': return 'Ago'; break;
		case '09': return 'Set'; break;
		case '10': return 'Oct'; break;
		case '11': return 'Nov'; break;
		case '12': return 'Dic'; break;
	}
}

function getFechaUlt($anio, $tipo, $origen) {
	$linkPG = Conectarse_POSTGRES();
	$sql = "select max(date(fecha)) as fecha from mef_carga_ws where anioeje=".$anio." and tipo='".$tipo."' and estado=1 ";
	$res = pg_query($linkPG, $sql);
	$row = pg_fetch_array ($res, 0);
	$fecha = $row["fecha"];
	$fecha = split("-",$fecha);
	$dia = $fecha[2];
	$mes = getMes($fecha[1]);
	$anio = $fecha[0];
	echo $dia.'-'.$mes.'-'.$anio;
}
?>
<td>
	<b>Notas:</b>
	<br>Los montos se muestran en Nuevos Soles
	<br>La columna % Avance representa la raz&oacute;n del Devengado entre el PIM, expresado en porcentajes
<br><b>Fuente:</b>
	<br>Ministerio de Econom&iacute;a y Finanzas - Consulta Amigable
	<br>Ultima actualizaci&oacute;n: <?php echo getFechaUlt('2017', 'P', '')?>
</td>
