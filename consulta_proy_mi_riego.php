<?php
/***************************************************************************************
Organizaci�n: 	Presidencia del Consejo de Ministros - PCM
Gerencia	: 	Secretar�a de Descentralizaci�n
Sistema		: 	Sistema Nacional de Informaci�n Geogr�fica - SAYWITE
Coordinador/Desarrollador TIC: Vitaly Uriel Cespedes Reynaga vitalyuri@hotmail.com/vitalyuri@gmail.com/969228151
Periodo		: 	Septiembre 2012 - 2013
***************************************************************************************/

if (isset($_GET['excel']) && ($_GET['excel']=='excel')) {
	header("Content-type:application/x-msexcel");
	$file=$_GET['capa'];
	header("Content-Disposition: attachment; filename=".$file.".xls");
	$imgtitulo='';
} else {
	$imgtitulo='<img src="imajes/desplegable.png" width="11" height="11">';
}

/*@session_start();
	if(empty($_SESSION['usuario'])){
        header('Location: ../index.php');
		}else{$cod_user = $_SESSION['usuario'];}*/
	include ('conexion_postgres.phtml');
	include ('injection/funciones.php');
	$player = 	limpiarCadena($_GET['olayer']);
	$pcampo = 	limpiarCadena($_GET['ocampo']);
	$pvalor = 	$_GET['ovalor'];
	$gid = $pvalor;
	$linkPG = Conectarse_POSTGRES(); 
	// Browse Data
	$sQueryS = " SELECT * from ";   
	$sQueryF = " peru_proy_mi_riego";
	$sQueryW = " WHERE gid = '" . $gid. "'";
	$sQuery = $sQueryS . $sQueryF . $sQueryW;
	$rsDet = pg_query( $linkPG , $sQuery );
	$titulo_ventana = 'proyecto mi riego - '.pg_fetch_result($rsDet,0,"\"tipo_pro_1\"").' '.pg_fetch_result($rsDet,0,"\"nombre_pro\"");
	$titulo = 'proyecto mi riego - '.pg_fetch_result($rsDet,0,"\"tipo_pro_1\"").'<br>'.pg_fetch_result($rsDet,0,"\"nombre_pro\"");
	include ('inc/consulta_cabecera.php');
?>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr><td>
	<table width="100%" class="marco_general">
	  <tr><td>
		<table width="100%" class="marco_individual">
		  <tr>
			<td>
			<table width="100%" class="titulo_general">
			  <tr>
				<td>&nbsp;&nbsp;UBICACI&Oacute;N GEOGR&Aacute;FICA</td>
			  </tr>
			</table>
			<table width='100%' align='center' class='general'>
				<thead>
				  <tr>
					<th width='33%'>Departamento</th>
					<th width='33%'>Provincia</th>
					<th width='33%'>Distrito</th>
				  </tr>
				</thead>
				<tbody>
<?php
		print "<tr>
				<td align='center' class='texto'>".pg_fetch_result($rsDet,0,"\"departamen\"")."</td>
				<td align='center' class='texto'>".pg_fetch_result($rsDet,0,"\"provincia\"")."</td>
				<td align='center' class='texto'>".pg_fetch_result($rsDet,0,"\"distrito\"")."</td>
			  </tr>";
?>
				</tbody>
			</table>
			</td>
		  </tr>
		</table>
		<br>
		<table width="100%" class="marco_individual">
		  <tr>
			<td>
			<table width="100%" class="titulo_general">
			  <tr>
				<td>&nbsp;&nbsp;DATOS Generales</td>
			  </tr>
			</table>
			<table width="100%" class="listado">
<?php
		print "<tr>";
		print "<td width='40%' class='lista_titulo'><b>C&ocute;digo del proyecto SNIP</b></td>";
		print "<td class='lista_texto'>" . pg_fetch_result($rsDet,0,"\"cod_snip\"") . "</td>";
		print "</tr>";
		print "<tr>";
		print "<td class='lista_titulo'><b>Nombre del proyecto</b></td>";
		print "<td class='lista_texto'>" . pg_fetch_result($rsDet,0,"\"nombre_pro\"") . "</td>";
		print "</tr>";

		print "<tr>";
		print "<td class='lista_titulo'><b>Cultivos Predonimantes</b></td>";
		print "<td class='lista_texto'>" .pg_fetch_result($rsDet,0,"\"tx_cult_pr\"");
		print "</tr>";

		print "<tr>";
		print "<td class='lista_titulo'><b>Centro Poblado</b></td>";
		print "<td class='lista_texto'>" . pg_fetch_result($rsDet,0,"\"tx_cen_pob\"") . "</td>";
		print "</tr>";
		
				print "<tr>";
		print "<td class='lista_titulo'><b>Unidad Ejecutora</b></td>";
		print "<td class='lista_texto'>" . pg_fetch_result($rsDet,0,"\"tx_ue_enc\"") . "</td>";
		print "</tr>";
		
				print "<tr>";
		print "<td class='lista_titulo'><b>Tipo de Poyecto</b></td>";
		print "<td class='lista_texto'>" . pg_fetch_result($rsDet,0,"\"tipo_pro_1\"") . "</td>";
		print "</tr>";
	
				print "<tr>";
		print "<td class='lista_titulo'><b>Hectareas Beneficiadas</b></td>";
		print "<td class='lista_texto'>" . pg_fetch_result($rsDet,0,"\"num_ha\"") . "</td>";
		print "</tr>";
		
				print "<tr>";
		print "<td class='lista_titulo'><b>Capacidad de Volumen</b></td>";
		print "<td class='lista_texto'>" . pg_fetch_result($rsDet,0,"\"num_m3\"") . "</td>";
		print "</tr>";
		
				print "<tr>";
		print "<td class='lista_titulo'><b>Metros Lineales</b></td>";
		print "<td class='lista_texto'>" . pg_fetch_result($rsDet,0,"\"num_m_line\"") . "</td>";
		print "</tr>";
		
				print "<tr>";
		print "<td class='lista_titulo'><b>Estado</b></td>";
		print "<td class='lista_texto'>" . pg_fetch_result($rsDet,0,"\"nom_estado\"") . "</td>";
		print "</tr>";
			
						print "<tr>";
		print "<td class='lista_titulo'><b>Porcentaje de avance</b></td>";
		print "<td class='lista_texto'>" . pg_fetch_result($rsDet,0,"\"pct_avance\"") . "</td>";
		print "</tr>";
	?> 
			</table>
			</td>
		  </tr>
		</table>
		<br>
		<table width="100%" class="marco_individual">
		  <tr>
			<td>
			<table width="100%" class="titulo_general">
			  <tr>
				<td>&nbsp;&nbsp;Consolidado proyectos mi riego <?php echo (pg_fetch_result($rsDet,0,"\"departamen\"")); ?> </td>
			  </tr>
			</table>
			<table width='100%' align='center' class='general'>
					<thead><tr><th>#</th>
							  <th>Departamento</th>
							  <th>Provincia</th>
							  <th>Distrito</th>
							  <th>Nombre del proyecto</th>
							  <th>Tipo de Poyecto</th>
							  <th>Estado</th>
							  <th>Porcentaje de avance</th>
							  </tr></thead>

							  
<?php
		$rsConsulta = pg_query( $linkPG , "select * from peru_proy_mi_riego   where  departamen = '".pg_fetch_result($rsDet,0,"\"departamen\"")."' and cod_tip_py = ".pg_fetch_result($rsDet,0,"\"cod_tip_py\"").";");
		$numRowsSBS = pg_num_rows($rsConsulta);
		$i=0;
		while($i<$numRowsSBS){
			$j=$i+1;
			if($j%2){
				$classe = "class='rowpar'";
			}else{
				$classe = "";
			}
			echo('<tr '.$classe.'><td class="texto11">'.$j.'</td>
					  <td class="texto11">'.pg_fetch_result($rsConsulta,$i,"\"departamen\"").'</td>
					  <td class="texto11">'.pg_fetch_result($rsConsulta,$i,"\"provincia\"").'</td>
					  <td class="texto11">'.pg_fetch_result($rsConsulta,$i,"\"distrito\"").'</td>
					  <td class="texto11">'.pg_fetch_result($rsConsulta,$i,"\"nombre_pro\"").'</td>
					  <td class="texto11">'.pg_fetch_result($rsConsulta,$i,"\"tipo_pro_1\"").'</td>
					  <td class="texto11">'.pg_fetch_result($rsConsulta,$i,"\"nom_estado\"").'</td> 
					  <td align="right">'.number_format(pg_fetch_result($rsConsulta,$i,"\"pct_avance\""),2).'%</td></tr>');
			$i++;
			$TotalConflic=$i;
		}
?>
			</table>
			</td></tr>
		</table>
				
		<table class='fuente_general' width="100%"><thead><tr><th><b>Fuente:</b>
			<br><?php echo(pg_fetch_result($rsDet,0,"\"fuente_inf\"").' - actualizado al '.pg_fetch_result($rsDet,0,"\"fech_actua\"")); ?>
		</th></tr></thead></table>
	<?php
	$name=$titulo;
	$capa='consulta_proy_mi_riego';
	if (!isset($_GET['excel'])) {
		$ruta=$capa.'.php?excel=excel&capa='.$capa.'&ovalor='.$gid;
		include 'inc/consulta_pie.php';
	}
	if (isset($_SESSION['usuario'])) {
		grabar_log_accesos('ingreso', $cod_user, $name, $descripcion);
	}
	?>
</td></tr>
	</table>
	</td></tr>
</table>
</body>

</html>

<?php include_once("./analyticstracking.php");?>