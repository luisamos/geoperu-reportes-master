<!DOCTYPE HTML>
<?php
@session_start();
include ('../conexion_postgres.phtml');
$linkPG = Conectarse_POSTGRES();
$limpiar=pg_query($linkPG,"truncate table data_ppss_beneficiarios;");
$i=1;
while($i<=7){
	if($i==1){
		$nom_campo=' cod_p65=1  and pension65>0';
		$num_beneficiarios = 'pension65';
		$nom_ppss='Pensión 65 (Usuarios)';
	}else if($i==2){
		$nom_campo=' cod_cmas=1  and cuid_dia>0';
		$nom_ppss='Cuna Más	(Niños)';
	}else if($i==3){
		$nom_campo=' cod_junt=1 and hog_abon>0';
		$nom_ppss='Juntos (Hogares)';
	}else if($i==4){
		$nom_campo=' con_fonco=1  and usu_est>0';
		$nom_ppss='Foncodes	(Usuarios)';
	}else if($i==5){
		$nom_campo=' cod_qal=1  and nin_atend>0';
		$nom_ppss='Qali Warma (Niños)';
	}else if($i==6){
		$nom_campo=' cod_cont=1 and contigo>0';
		$nom_ppss='Contigo (Usuarios)';
	}
	else if($i==7){
		$nom_campo=' con_tamb=1  and tam_nubem>0';
		$nom_ppss='Pais - Tambos(Beneficiarios)';
	}
	$consppss=pg_query($linkPG,"select  gid, objectid_1, ubigeo, cod_dpto, nom_dpto, cod_prov, nom_prov, cod_dist, nom_dist, cod_fecha, cod_anno, ubigeo_1, departamen, provincia, distrito, cod_p65, cod_cmas, cod_junt, con_fonco, cod_qal, cod_cont, con_tamb, pension65, hog_ads, hog_abon, cuid_dia, acompa_fam, usu_est, proy_culm, proy_eje, hog_hakuw, acu_hakuw, nin_atend, num_iiee, contigo, tam_pser, tam_atrea, tam_nubem, num_ppss, ran_ppss, cod_ran, fuente, fuente1, fuente2, fuente3, fuente3_1, fuente4, fuente5, fuente5_1, fuente6, fuente7, fuente7_1	from peru_programas_sociales2 where ".$nom_campo.";");
    if(pg_num_rows($consppss)>0){
		$j=0;
		while($j<pg_num_rows($consppss)){
		$gid=$i.$j;
		if($i==1){
		$num_beneficiarios=pg_fetch_result($consppss,$j,"\"pension65\"");
		}else if($i==2){
			$num_beneficiarios=pg_fetch_result($consppss,$j,"\"cuid_dia\"");
		}else if($i==3){
			$num_beneficiarios=pg_fetch_result($consppss,$j,"\"hog_abon\"");
		}else if($i==4){
			$num_beneficiarios=pg_fetch_result($consppss,$j,"\"usu_est\"");
		}else if($i==5){
			$num_beneficiarios=pg_fetch_result($consppss,$j,"\"nin_atend\"");
		}else if($i==6){
			$num_beneficiarios=pg_fetch_result($consppss,$j,"\"contigo\"");
		}else if($i==7){
			$num_beneficiarios=pg_fetch_result($consppss,$j,"\"tam_nubem\"");
		}
		$instppss=pg_query($linkPG,"INSERT INTO data_ppss_beneficiarios (gid,cod_dpto,nom_dpto,nom_prov,cod_prov,cod_dist,nom_dist,cod_fecha,cod_anno,nom_ppss,num_beneficiarios,fuente)
		values(".$gid.",'".pg_fetch_result($consppss,$j,"\"cod_dpto\"")."','".pg_fetch_result($consppss,$j,"\"nom_dpto\"")."','".pg_fetch_result($consppss,$j,"\"nom_prov\"")."',
		'".pg_fetch_result($consppss,$j,"\"cod_prov\"")."','".pg_fetch_result($consppss,$j,"\"cod_dist\"")."','".pg_fetch_result($consppss,$j,"\"nom_dist\"")."',
		'".pg_fetch_result($consppss,$j,"\"cod_fecha\"")."','".pg_fetch_result($consppss,$j,"\"cod_anno\"")."','".$nom_ppss."',".$num_beneficiarios.",
															   '".pg_fetch_result($consppss,$j,"\"fuente\"")."');");
															
		$j++;	
	}
	 	
		
	}	

$i++;	
}
$conscantidad=pg_query($linkPG,"select cod_dist,count(*) as cantidad from data_ppss_beneficiarios group by cod_dist");
$l=0;
while($l<pg_num_rows($conscantidad)){
	$upcantidad=pg_query($linkPG,"update data_ppss_beneficiarios set cantidad=".pg_fetch_result($conscantidad,$l,"\"cantidad\"")." 
	                                   where cod_dist = '".pg_fetch_result($conscantidad,$l,"\"cod_dist\"")."';");
$l++;
}

if($conscantidad>0){
	echo('<p align="center">--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</p><p align="center"><b>LOS DATOS DE BENEFICIARIOS -  FUERON ACTUALIZADOS CORRECTAMENTE................!!!!!</b></p><br>');
	pg_free_result($conscantidad);
	pg_free_result($limpiar);
	pg_free_result($upcantidad);
	pg_free_result($instppss);
}
//pg_close($linkPG);

?>