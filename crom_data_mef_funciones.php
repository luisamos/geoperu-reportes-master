<?php
	@session_start();
	include ('../conexion_postgres.phtml');
	$linkPG = Conectarse_POSTGRES();
		//$borr=pg_query($linkPG,"delete from data_mef_funciones;alter sequence data_mef_funciones_gid_seq restart 1;alter sequence data_mef_funciones_gid_seq restart 1;");
		$borr=pg_query($linkPG,"delete from data_mef_funciones;");
		pg_free_result($borr);
		$scrip 			= 	" SELECT i.anioeje anio,
									   i.idnivelgobierno niv_gob,
									   i.departamento cod_dpto, 
									   ur.nom_dpto nom_dpto,
									   i.provincia as cod_prov, 
									   up.nom_prov nom_prov,
									   i.distrito as cod_dist, 
									   ud.nom_dist nom_dist,
									   i.idsector, 
									   s.nombre sector, 
									   i.idfuncion, 
									   f.nombre funcion, 
									   tipoactproy tipactproy, 
									   sum(pia) pia, 
									   sum(pim) pim, 
									   sum(devengado) devengado, 
									   case when sum(pim)>0 then sum(devengado)*100/sum(pim) else 0 end as avance
								FROM mef_itemgastopipsg i inner join mef_sectorxanio s on i.anioeje=s.anioeje and i.idsector=s.idsector and s.anioeje = 2017 and s.estado = 'A'
								inner join mef_funcionxanio f on i.idfuncion = f.idfuncion and f.anioeje = 2017 and f.estado = 'A'
								left join (select distinct cod_dpto, nom_dpto from mef_ubigeo) ur ON ur.cod_dpto = i.departamento 
								left join (select distinct cod_dpto, nom_dpto, cod_prov, nom_prov from mef_ubigeo) up ON up.cod_dpto = i.departamento AND substring(up.cod_prov,3,4) = i.provincia 
								left join (select distinct cod_dpto, nom_dpto, cod_prov, nom_prov, cod_dist, nom_dist from mef_ubigeo) ud ON ud.cod_dpto = i.departamento AND substring(ud.cod_prov,3,2) = i.provincia AND substring(ud.cod_dist,5,2) = i.distrito
																	AND i.departamento <> '98'
																   GROUP BY i.anioeje, departamento, ur.nom_dpto, provincia, up.nom_prov, distrito, ud.nom_dist, i.idsector, s.nombre, idnivelgobierno, i.idfuncion, f.nombre, tipoactproy";
//echo($scrip);									
		$consuGeneral 	= 	pg_query($linkPG,$scrip);
		$numRow 		=	pg_num_rows($consuGeneral);
		$i=0;
		while($i<$numRow){
			$anio 		= pg_fetch_result($consuGeneral,$i,"\"anio\"");
			$niv_gob 	= pg_fetch_result($consuGeneral,$i,"\"niv_gob\"");
			$cod_dpto 	= pg_fetch_result($consuGeneral,$i,"\"cod_dpto\"");
			$nom_dpto 	= pg_fetch_result($consuGeneral,$i,"\"nom_dpto\"");			
			$cod_prov 	= pg_fetch_result($consuGeneral,$i,"\"cod_prov\"");
			$nom_prov 	= pg_fetch_result($consuGeneral,$i,"\"nom_prov\"");
			$cod_dist 	= pg_fetch_result($consuGeneral,$i,"\"cod_dist\"");
			$nom_dist 	= pg_fetch_result($consuGeneral,$i,"\"nom_dist\"");
			$idsector 	= pg_fetch_result($consuGeneral,$i,"\"idsector\"");
			$sector 	= pg_fetch_result($consuGeneral,$i,"\"sector\"");
			$idfuncion 	= pg_fetch_result($consuGeneral,$i,"\"idfuncion\"");
			$funcion 	= pg_fetch_result($consuGeneral,$i,"\"funcion\"");
			$tipactproy = pg_fetch_result($consuGeneral,$i,"\"tipactproy\"");
			$pia 		= pg_fetch_result($consuGeneral,$i,"\"pia\"");
			$pim 		= pg_fetch_result($consuGeneral,$i,"\"pim\"");
			$devengado 	= pg_fetch_result($consuGeneral,$i,"\"devengado\"");
			$avance 	= number_format(pg_fetch_result($consuGeneral,$i,"\"avance\""),2);
			$fuente 	= 'MEF - Ministerio de Economía y Finanzas. (DGPP) Dirección General de Presupuesto Público. Setiembre de 2,017';
			$difpimdev = $pim - $devengado;
				if($avance<=50){
					$rango 	 = '0.0% - 50.0%';
					$cod_ran =	1; 
				}else if($avance>50){
					$rango = '50.0% - 100.0%';
					$cod_ran =	2;
				}
				if($tipactproy==2){
					$des_tipro ='PROYECTOS';
				}else if($tipactproy==3){
					$des_tipro ='ACTIVIDADES';
				}	
				if($niv_gob == 'M'){
					$nombre = 'GOBIERNO LOCAL';
				}else if($niv_gob == 'E'){
					$nombre = 'GOBIERNO NACIONAL';
				}elseif($niv_gob == 'R'){
					$nombre = 'GOBIERNO REGIONAL';
				}
				$cod_prov=$cod_dpto.$cod_prov;
				$cod_dist=$cod_prov.$cod_dist;
				if($cod_prov=='1501'){
					$cod_dpto='26';
				}
				$link="<a href=''programas/proyectos/reportes/rep_mef_intervenciones.php?region=".$cod_dpto."&tipactproy=".$tipactproy."'' target=''_blank''>Ver</a>";
				$inset = pg_query($linkPG,"INSERT INTO data_mef_funciones(anio,anioeje,cod_dpto,nom_dpto,cod_prov,nom_prov,cod_dist,nom_dist,niv_gob,nombre,cod_region,idsector,sector,idfuncion,funcion,tipactproy,
																				des_tipro,pia,pim,devengado,avance,rango,cod_ran,link,ubigeo,fuente)
																		  VALUES (".$anio.",".$anio.",'".$cod_dpto."','".$nom_dpto."','".$cod_prov."','".$nom_prov."','".$cod_dist."','".$nom_dist."','".$niv_gob."','".$nombre."',
																				'".$cod_dpto."','".$idsector."','".$sector."',
																				'".$idfuncion."','".$funcion."',".$tipactproy.",'".$des_tipro."',".$pia.",".$pim.",".$devengado.",
																				".$avance.",'".$rango."',".$cod_ran.",'".$link."','".$cod_dpto."','".$fuente."');");
				$i++;
			}
		echo('<p align="center"><b>LOS DATOS DE PROYECTOS DE INVERSION PUBLICA<BR>GOBIERNO CENTRAL<br>GOBIERNO REGIONAL<br>GOBIERNO LOCAL <br>FUERON ACTUALIZADOS CORRECTAMENTE................!!!!!</b></p><br>');
	pg_free_result($consuGeneral);
	pg_free_result($inset);
	pg_close($linkPG);
	?>