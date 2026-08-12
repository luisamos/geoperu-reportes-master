<!DOCTYPE HTML>
<?php
@session_start();
include ('../conexion_postgres.phtml');
$linkPG = Conectarse_POSTGRES();
$annio  = date(Y);
$sqlCons=pg_query($linkPG,"SELECT i.departamento || i.provincia ||i.distrito as ubigeo, sum(i.pia) AS pia, sum(i.pim) AS pim, sum(i.companual) AS companual, sum(i.atencioncompmensual) AS compmen, sum(i.devengado) AS devengado, sum(i.girado) AS girado, case when sum(i.pim)>0 then sum(i.devengado)*100/sum(i.pim) else 0 end as avance from mef_itemgastopipsg i inner join mef_proyectoxanio pa on i.anioeje = pa.anioeje AND i.idproyecto = pa.idproyecto AND i.idproyectosnip = pa.idproyectosnip  where i.idnivelgobierno = 'M' and i.anioeje = ".$annio." and pa.estado='A' and i.tipoactproy =2 group by i.departamento || i.provincia ||i.distrito order by i.departamento || i.provincia ||i.distrito");
$numRowSqlCons=pg_num_rows($sqlCons);
if($numRowSqlCons>0){
	$i=0;
	while($i<$numRowSqlCons){
		$pia         = pg_fetch_result($sqlCons,$i,"\"pia\"");
		$pim         = pg_fetch_result($sqlCons,$i,"\"pim\"");
		$devengado   = pg_fetch_result($sqlCons,$i,"\"devengado\"");
		$girado      = pg_fetch_result($sqlCons,$i,"\"girado\"");
		$avance      = pg_fetch_result($sqlCons,$i,"\"avance\"");
		$companual   = pg_fetch_result($sqlCons,$i,"\"companual\"");
		$comp_mens   = pg_fetch_result($sqlCons,$i,"\"compmen\"");
		$ubigeo      = pg_fetch_result($sqlCons,$i,"\"ubigeo\"");
	    $upRowSqlCons= pg_query($linkPG,"update peru_info_gl set pia=".$pia.", pim=".$pim.", devengado=".$devengado.", girado=".$girado.", avance=".$avance.", comp_anual=".$companual.",comp_mens=".$comp_mens." where cod_dist='".$ubigeo."'");
	   $i++;
	}
	$text='';
	pg_free_result($upRowSqlCons);
}else{
	$text='NO';
}
   echo('<p align="center">--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</p><p align="center"><b>................LOS DATOS DE RENAMU - PROVINCIAL Y DISTRITAL '.$text.' FUERON ACTUALIZADOS CORRECTAMENTE................</b></p><br>');
pg_free_result($sqlCons);
pg_close($linkPG);
?>