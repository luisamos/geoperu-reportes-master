<?php
//@session_start();
include ('../../usuarios/inc/funciones.php');
include ('../conexion_postgres.phtml');
$linkPG = Conectarse_POSTGRES();
$anio=2017;
$sqlFecha=pg_query($linkPG,"select max(date(fecha)) as fecha  from mef_carga_ws where tipo='P' and anioeje=".$anio." AND estado = 1");

$fuente='Fuente: Ministerio de Econom&iacute;a y Finanzas - Consulta Amigable Ultima actualización: '.fecha(pg_fetch_result($sqlFecha,0,"fecha"));
$sqlMantenimiento=pg_query($linkPG," drop table mef_itemgastopipsg_tmp;CREATE TABLE mef_itemgastopipsg_tmp(id serial NOT NULL,anioeje integer NOT NULL,idnivelgobierno character varying(1) NOT NULL,idsector character varying(5) NOT NULL,idpliego character varying(5) NOT NULL,idejecutora character varying(5) NOT NULL,secejec character varying(10) NOT NULL,idfuente character varying(5) NOT NULL,idrubro character varying(5) NOT NULL,idproyecto integer NOT NULL,idproyectosnip integer,departamento character varying(10),provincia character varying(10),distrito character varying(10),pia double precision,pim double precision,companual double precision,atencioncompmensual double precision,devengado double precision,girado double precision,secfunc character varying(10),idmeta character varying(10),idfinalidad character varying(10),idtiporecurso character varying(10),idcomponente character varying(10),tipoactproy integer,idfuncion character varying(5),idprograma character varying(5));INSERT INTO mef_itemgastopipsg_tmp(anioeje, idnivelgobierno, idsector, idpliego, idejecutora,secejec, idfuente, idrubro, idproyecto, idproyectosnip, departamento,provincia, distrito, pia, pim, companual, atencioncompmensual,devengado, girado, secfunc, idmeta, idfinalidad, idtiporecurso,idcomponente, tipoactproy, idfuncion, idprograma) SELECT anioeje, idnivelgobierno, idsector, idpliego, idejecutora, secejec,idfuente, idrubro, idproyecto, idproyectosnip, departamento,provincia, distrito, pia, pim, companual, atencioncompmensual,devengado, girado, secfunc, idmeta, idfinalidad, idtiporecurso,idcomponente, tipoactproy, idfuncion, idprograma FROM public.mef_itemgastopipsg;");

/*$sqlMantenimiento=pg_query($linkPG,"delete from mef_itemgastopipsg_tmp where anioeje = 2011;delete from mef_itemgastopipsg_tmp where anioeje = 2012;delete from mef_itemgastopipsg_tmp where anioeje = 2013;delete from mef_itemgastopipsg_tmp where anioeje = 2014;delete from mef_itemgastopipsg_tmp where anioeje = 2015;delete from mef_itemgastopipsg_tmp where anioeje = 2016;delete from mef_itemgastopipsg_tmp where anioeje = 2017;delete from mef_itemgastopipsg_tmp where anioeje = 2018;delete from mef_itemgastopipsg_tmp where anioeje = 2019;INSERT INTO mef_itemgastopipsg_tmp(anioeje, idnivelgobierno, idsector, idpliego, idejecutora,secejec, idfuente, idrubro, idproyecto, idproyectosnip, departamento,provincia, distrito, pia, pim, companual, atencioncompmensual,devengado, girado, secfunc, idmeta, idfinalidad, idtiporecurso,idcomponente, tipoactproy, idfuncion, idprograma) SELECT anioeje, idnivelgobierno, idsector, idpliego, idejecutora, secejec,idfuente, idrubro, idproyecto, idproyectosnip, departamento,provincia, distrito, pia, pim, companual, atencioncompmensual,devengado, girado, secfunc, idmeta, idfinalidad, idtiporecurso,idcomponente, tipoactproy, idfuncion, idprograma FROM public.mef_itemgastopipsg;");*/
if($sqlMantenimiento>0){
	echo('<br>----------------------------------------------------------------------------------</br>Tabla maestra actualizada correctamente<br>----------------------------------------------------------------------------------</br>');
}
pg_free_result($sqlMantenimiento);

pg_free_result($sqlFecha);
$m=1;
while($m<=3){
	if($m==1){//Rubro canon
      $idrubro=" and idrubro='18'";
      $total_var ='tot_canon';
	  $sum_var   ='sum_tot_ca';
	  $cod_var   ='cod_rcanon';
	  $rang_var  ='rang_canon';
	  $tabla  =' data_canon_distrito';
	  $mensaje='Se actualizaron los datos para la capa "<b><u>Distribuci&oacute;n del Canon</u></b>"';
	  $borrar=' DROP table '.$tabla;
	  $crear='CREATE TABLE '.$tabla.'(gid serial NOT NULL,ejecutora text,distrito text,pim_proy double precision,pim_activ double precision,tot_canon double precision,ubigeo_mef text,cnt_ubigeo double precision,sum_tot_ca double precision,cod_rcanon double precision,rang_canon text,pob_2017 double precision,cod_dpto text,nom_dpto text,cod_prov text,nom_prov text,cod_dist text,nom_dist text,fuente text);';
	  $permiso='GRANT SELECT, INSERT, UPDATE, DELETE ON '.$tabla.' TO sayhuite;';
	}else if($m==2){//Rubro FONCOMUN
	  $idrubro=" and idrubro='07'";
	  $total_var='tot_foncom';
	  $sum_var='sum_tot_fo';
	  $cod_var='cod_rfoncm';
	  $rang_var='rang_foncm';
	  $tabla  =' data_foncomun_distrital';
	  $mensaje='Se actualizaron los datos para la capa "<b><u>Distribuci&oacute;n del FONCOMUN</u></b>"';
	  $borrar=' DROP table '.$tabla;
	  $crear='CREATE TABLE '.$tabla.'(gid serial NOT NULL,ejecutora text,distrito text,pim_proy double precision,pim_activ double precision,ubigeo_mef text,tot_foncom double precision,cnt_ubigeo double precision,sum_tot_fo double precision,cod_rfoncm double precision,rang_foncm text,pob_2017 double precision,cod_dpto text,nom_dpto text,cod_prov text,nom_prov text,cod_dist text,nom_dist text,fuente text);';
	  $permiso='GRANT SELECT, INSERT, UPDATE, DELETE ON '.$tabla.' TO sayhuite;';
	}else if ($m==3){//Presupuesto municipal
	  $idrubro=" ";
	  $total_var='total_im';
	  $sum_var='sum_tot_im';
	  $cod_var='cod_rim';
	  $rang_var='rang_im';
	  $tabla  =' data_ingreso_municipal';
	  $mensaje='Se actualizaron los datos para la capa "<b><u>Presupuesto Municipal</u></b>"';
	  $borrar=' DROP table '.$tabla;
	  $crear='CREATE TABLE '.$tabla.'(gid serial NOT NULL,ejecutora text,distrito text,pim_proy double precision,pim_activ double precision,total_im double precision,ubigeo_mef text,cnt_ubigeo double precision,sum_tot_im double precision,cod_rim double precision,rang_im text,pob_2017 double precision,cod_dpto text,nom_dpto text,cod_prov text,nom_prov text,cod_dist text,nom_dist text,fuente text);';
	  $permiso='GRANT SELECT, INSERT, UPDATE, DELETE ON '.$tabla.' TO sayhuite;';
	}
	$sql1=pg_query($linkPG,$borrar);
	pg_free_result($sql1);
	$sql2=pg_query($linkPG,$crear);
	pg_free_result($sql2);
	
	$sqlDist=pg_query($linkPG,"SELECT ejecutora, ubigeo, region, provincia, distrito, sum(pimp) pimp, sum(pima) pima, sum(pim) total from (
		select 
		e.nombre ejecutora
		,i.departamento||i.provincia||i.distrito ubigeo
		,ur.nom_dpto region
		,up.nom_prov provincia
		,ud.nom_dist distrito
		,pimp.pim pimp
		,pima.pim pima
		,i.pim
		FROM mef_itemgastopipsg_tmp i 
		inner JOIN mef_ejecutoraxanio e ON e.anioeje = i.anioeje 
		AND e.idsector = i.idsector AND e.idpliego = i.idpliego AND e.idejecutora = i.idejecutora AND e.secejec = i.secejec AND e.idnivelgobierno = i.idnivelgobierno AND e.estado = 'A'
		left join (SELECT id, pim FROM mef_itemgastopipsg_tmp WHERE tipoactproy = 2) as pimp on pimp.id = i.id
		left join (SELECT id, pim FROM mef_itemgastopipsg_tmp WHERE tipoactproy = 3) as pima on pima.id = i.id
		left join (select distinct cod_dpto, nom_dpto from mef_ubigeo) ur ON ur.cod_dpto = i.departamento 
		left join (select distinct cod_dpto, nom_dpto, cod_prov, nom_prov from mef_ubigeo) up ON up.cod_dpto = i.departamento AND substring(up.cod_prov,3,4) = i.provincia 
		left join (select distinct cod_dpto, nom_dpto, cod_prov, nom_prov, cod_dist, nom_dist from mef_ubigeo) ud ON ud.cod_dpto = i.departamento AND substring(ud.cod_prov,3,4) = i.provincia AND substring(ud.cod_dist,5,6) = i.distrito
		WHERE i.anioeje    = ".$anio." AND e.anioeje = ".$anio." AND e.estado = 'A' AND i.idnivelgobierno = 'M' ".$idrubro."
		) Q1
		group by ejecutora, ubigeo, region, provincia, distrito
		order by ubigeo;");
		$i=0;
		while($i<pg_num_rows($sqlDist)){
		$ejecutora   = pg_fetch_result($sqlDist,$i,"\"ejecutora\"");
		$ubigeo_mef  = pg_fetch_result($sqlDist,$i,"\"ubigeo\"");
		$nom_dpto    = pg_fetch_result($sqlDist,$i,"\"region\"");
		$nom_prov    = pg_fetch_result($sqlDist,$i,"\"provincia\"");
		$nom_dist    = pg_fetch_result($sqlDist,$i,"\"distrito\"");
		$distrito    = pg_fetch_result($sqlDist,$i,"\"distrito\"");
		$pim_proy    = pg_fetch_result($sqlDist,$i,"\"pimp\"");
		if($pim_proy==''){
		$pim_proy=0;
		}
		$pim_activ   = pg_fetch_result($sqlDist,$i,"\"pima\"");
		if($pim_activ==''){
		$pim_activ=0;
		}
		$total   = pg_fetch_result($sqlDist,$i,"\"total\"");
		$cod_dpto= substr($ubigeo_mef, 0, 2);
		$cod_prov= substr($ubigeo_mef, 0, 4);
		$cod_distrito= substr($ubigeo_mef, 4, 2);
		if($cod_distrito=='99'){
		  $cod_dist=$cod_prov.'01';
		}else{
		  $cod_dist=$ubigeo_mef;
		}
		$consPoblacion=pg_query($linkPG,"select pob_py_17 from data_pobl_proy_dist where cod_dist='".$cod_dist."'");
		$pob_2017=pg_fetch_result($consPoblacion,0,"\"pob_py_17\"");
		$upDist    =pg_query($linkPG,"insert into ".$tabla."(ejecutora,ubigeo_mef,cod_dpto,nom_dpto,cod_prov,nom_prov,cod_dist,nom_dist,distrito,pim_proy,pim_activ,".$total_var.",pob_2017,fuente)values('".$ejecutora."','".$ubigeo_mef."','".$cod_dpto."','".$nom_dpto."','".$cod_prov."','".$nom_prov."','".$cod_dist."','".$nom_dist."','".$distrito."',".$pim_proy.",".$pim_activ.",".$total.",".$pob_2017.",'".$fuente."');");
	$i++;
	}
	pg_free_result($sqlDist);
$sqlCantidad=pg_query($linkPG,"select count(*) as cantidad,cod_dist,sum(".$total_var.") as sum_tot from ".$tabla." group by cod_dist,".$total_var."");
    $j=0;
	while($j<pg_num_rows($sqlCantidad)){
        $cnt_ubigeo=pg_fetch_result($sqlCantidad,$j,"\"cantidad\"");
        $sum_tot=pg_fetch_result($sqlCantidad,$j,"\"sum_tot\"");
        $cod_dist=pg_fetch_result($sqlCantidad,$j,"\"cod_dist\"");
        if($sum_tot<=500000){
           $cod_r=1;
           $rang='Hasta 0.5 M';
        }if($sum_tot>500000 and $sum_tot<=1000000){
        	$cod_r=2;
        	$rang='0.5 a 1.0 M';
        }if($sum_tot>1000000 and $sum_tot<=10000000){
        	$cod_r=3;
        	$rang='1.0 a 10 M';
        }if($sum_tot>10000000 and $sum_tot<=50000000){
        	$cod_r=4;
        	$rang='10 a 50 M';
        }if($sum_tot>50000000){
        	$cod_r=5;
        	$rang='Más de 50 M';
        }
        $upCantidad=pg_query($linkPG,"update ".$tabla." set cnt_ubigeo=".$cnt_ubigeo.",".$sum_var."=".$sum_tot.",".$cod_var."=".$cod_r.",".$rang_var."='".$rang."' where cod_dist='".$cod_dist."'");
	 $j++;
	}
	pg_free_result($upCantidad);
	pg_free_result($sqlCantidad);
		echo('<br>----------------------------------------------------------------------------------</br>'.$mensaje.'<br>----------------------------------------------------------------------------------</br>');
		
		//$sql3=pg_query($linkPG,$permiso);
        //pg_free_result($sql3);
		$m++;
}

pg_free_result($consPoblacion);
pg_free_result($upDist);
pg_close($linkPG);
?>