<?php
	$rutaConsulta = isset($_SERVER['HTTP_X_REQUEST_URI']) ? $_SERVER['HTTP_X_REQUEST_URI'] : $_SERVER['REQUEST_URI'];

	$conNivel=pg_query($linkPG,"select i.niv_descarga,
	                               c.nom_tabla 
	                            from def_capa_informacion i left join def_capa c on  i.idcapa=c.idcapa where c.capaxml  = '". $player."' and i.est_descarga=1;");
    $niv_descarga = pg_fetch_result($conNivel,0,"\"niv_descarga\"");
    $nom_tabla    = pg_fetch_result($conNivel,0,"\"nom_tabla\"");
    if($niv_descarga==0){
      $cadena='!...No está habilitado nivel de descarga, comuníquese con los administradores del proyecto...!';
    }elseif($niv_descarga==1){
    	$cadena='Nacional<input type="radio" name = "niv_descarga" value ="1">
                                    <input type="submit"  value="Consulta"/>';
    }elseif($niv_descarga==2){
    	$cadena='Departamental<input type="radio" name = "niv_descarga" value ="2">
                                    <input type="submit"  value="Consulta"/>';
    }elseif($niv_descarga==3){
        $cadena='Departamental<input type="radio" name = "niv_descarga" value ="2">
                                    Provincial<input type="radio" name = "niv_descarga" value ="3">
                                    <input type="submit"  value="Consulta"/>';
    }elseif($niv_descarga==4){
        $cadena='Departamental<input type="radio" name = "niv_descarga" value ="2">
                                    Provincial<input type="radio" name = "niv_descarga" value ="3">
                                    Distrital<input type="radio" name = "niv_descarga" value ="4">
                                    <input type="submit"  value="Consulta" class="ml-button-12"/>';
    }
	echo('<br><table width="100%" class="marco_individual">
		  <tr>
			<td>
				<div id="u6" style="width: 100%;">
					<h1>
						<table width="100%" class="titulo_general">
							<tr>
								<td class="numero1" colspan="7">'.$imgtitulo.'&nbsp;&nbsp;DESCARGA DE DATOS</td>
							</tr>
						</table>
					</h1>
				<div>
					<form method="post" action = "'.$rutaConsulta.'&descarga=1">
						<table width="100%" class="general">
							<tr>
								<td align="left" colspan="7">
                                    '.$cadena.'
								</td>
							</tr>
						</table>
					</form>');
		$descarga = $_GET['descarga'];
		if($descarga=='1'){
			$niv_descarga = isset($_POST["niv_descarga"])?$_POST["niv_descarga"]:$_GET["niv_descarga"];
				if($niv_descarga!=''){
					$ordenamiento = getExisteCampo($niv_descarga,$nom_tabla);
					if($niv_descarga=='1'){
						$ubigeconsult   ='';
						$ubigeconsultval='';
						$ordenamiento   = 'nom_dpto';
					}else if($niv_descarga=='2'){
						$cod_dpto       = pg_fetch_result($rsDet,0,"cod_dpto");
						$ubigeconsult   = ' where cod_dpto = ';
						$ubigeconsultval= "'".$cod_dpto."'";
						$ordenamiento   = ' cod_dpto';
					}else if($niv_descarga=='3'){
						$cod_prov       = pg_fetch_result($rsDet,0,"cod_prov");
						$ubigeconsult   = ' where cod_prov = ';;
						$ubigeconsultval= "'".$cod_prov."'";
						$ordenamiento   = ' cod_dist';
					}else if($niv_descarga=='4'){
						$cod_dist       = pg_fetch_result($rsDet,0,"cod_dist");
						$ubigeconsult   = ' where cod_dist =';
						$ubigeconsultval= "'".$cod_dist."'";
						$ordenamiento   = ' cod_dpto,cod_prov,cod_dist';
					}
					$nom_tabla=getComDesc($player);
					$consCampos= pg_query($linkPG,"select * from def_dic_tabla where nom_tabla = '".$nom_tabla."' and descargar = 1 order by ordencampo desc ");
					$i=0;
					$campos_nom_cam_reporte='';;
					$campos_nom_campo='';
					
					while($i<pg_num_rows($consCampos)){
							$nom_campo=pg_fetch_result($consCampos,$i,"\"nom_campo\"").' as campo'.$i.',';
							$campos_nom_campo= $nom_campo.$campos_nom_campo;
							$nom_cam_reporte=pg_fetch_result($consCampos,$i,"\"nom_cam_reporte\"");
							$campos_nom_cam_reporte='<th>'.$nom_cam_reporte.$campos_nom_cam_reporte.'</th>';
						$i++;				
					}
					//$campos_nom_cam_reporte = substr($campos_nom_cam_reporte,0,-1);
					$campos_nom_campo = substr($campos_nom_campo,0,-1);
					echo('<table width="100%" class="general"><thead><tr><th>Nro.</th>'.$campos_nom_cam_reporte.'</tr></thead>');
						$listReporte=pg_query($linkPG,"select ".$campos_nom_campo." from ".getComDesc($player).$ubigeconsult.$ubigeconsultval." order by ".$ordenamiento.";");
						$k=0;
						while($k<pg_num_rows($listReporte)){
							  $s=0;
							  $j=$k+1;
							  $campopueva='';
							  while($s<pg_num_rows($consCampos)){
								  $campo=pg_fetch_result($listReporte,$k,"\"campo\"".$s);
								  $campopueva='<td class="numero1">'.$campo.$campopueva.'</td>';
								  $s++;
							  }
							echo('<tr><td class="numero1">'.$j.'</td>'.$campopueva.'</tr>');
							$k++;
						}
					
					echo('</table>');
}else{
            	echo('<p class="educacion1"><b>¡Debe seleccionar el nivel de descarga!</b></p>');
            }
					echo('</div></div></td>
				  </tr>
				</table>');

}else{
	echo('</td>
		  </tr>
		</table>');
}
?>
