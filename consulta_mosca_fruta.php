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
	$sQueryF = " data_mosta_fruta";
	$sQueryW = " WHERE gid = '" . $gid. "'";
	$sQuery = $sQueryS . $sQueryF . $sQueryW;
	$rsDet = pg_query( $linkPG , $sQuery );
	$titulo_ventana = 'Detalle del proyecto mosca de la fruta';
	$titulo = $titulo_ventana;
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
				<td align='center' class='texto'>".pg_fetch_result($rsDet,0,"\"nom_dpto\"")."</td>
				<td align='center' class='texto'>".pg_fetch_result($rsDet,0,"\"nom_prov\"")."</td>
				<td align='center' class='texto'>".pg_fetch_result($rsDet,0,"\"nom_dist\"")."</td>
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
		print "<td width='60%' class='lista_titulo'><b>Descripci&oacute;n del estado del &aacute;rea intervenida</b></td>";
		print "<td class='lista_texto'>" . pg_fetch_result($rsDet,0,"\"estado\"") . "</td>";
		print "</tr>";
		print "<tr>";
		print "<td class='lista_titulo'><b>Hect&aacute;rea hospedante ( afectada de la mosca)</b></td>";
		print "<td class='lista_texto'>" . number_format(pg_fetch_result($rsDet,0,"\"hosped_ha\""),2) . "</td>";
		print "</tr>";

		print "<tr>";
		print "<td class='lista_titulo'><b>Hect&aacute;rea no hospedante ( area no afectada de la mosca)</b></td>";
		print "<td class='lista_texto'>" .number_format(pg_fetch_result($rsDet,0,"\"nohospe_ha\""),2);
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
				<td>&nbsp;&nbsp;Consolidado proyectos mosca de la fruta en la provincia  <?php echo (pg_fetch_result($rsDet,0,"\"nom_prov\"")); ?> </td>
			  </tr>
			</table>
			<table width='100%' align='center' class='general'>
					<thead><tr><th>#</th>
							  <th>Departamento</th>
							  <th>Provincia</th>
							  <th>Distrito</th>
							  <th>estado</th>
							  <th>Hectareas afectadas</th>
							  <th>Hectareas no afectadas</th>
							  </tr></thead>

							  
<?php
		$rsConsulta = pg_query( $linkPG , "select * from data_mosta_fruta   where  cod_prov = '".pg_fetch_result($rsDet,0,"\"cod_prov\"")."';");
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
					  <td class="texto11">'.pg_fetch_result($rsConsulta,$i,"\"nom_dpto\"").'</td>
					  <td class="texto11">'.pg_fetch_result($rsConsulta,$i,"\"nom_prov\"").'</td>
					  <td class="texto11">'.pg_fetch_result($rsConsulta,$i,"\"nom_dist\"").'</td>
					  <td class="texto11">'.pg_fetch_result($rsConsulta,$i,"\"estado\"").'</td>
					  <td class="texto11" align="right">'.number_format(pg_fetch_result($rsConsulta,$i,"\"hosped_ha\""),2).'</td>
					  <td class="texto11" align="right">'.number_format(pg_fetch_result($rsConsulta,$i,"\"nohospe_ha\""),2).'</td> 
					</tr>');
			$i++;
			$TotalConflic=$i;
		}
?>
			</table>
			</td></tr>
		</table>
				
		<table class='fuente_general' width="100%"><thead><tr><th><b>Fuente:</b>
			<br><?php echo(pg_fetch_result($rsDet,0,"\"fuente\"")); ?>
		</th></tr></thead></table>
	<?php
	$name=$titulo;
	$capa='consulta_mosca_fruta';
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