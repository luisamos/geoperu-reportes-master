<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	    <title><?php echo $titulo_ventana;?></title>
	</head>
	<script language="javascript" type="text/javascript" src="tab/jquery-1.3.2.min.js"></script>
	<script language="javascript" type="text/javascript" src="tab/h2c_acordeon.js"></script>
	<link href="tab/h2c_acordeon.css" rel="stylesheet" type="text/css">
	<link href="css/estilos.css" rel="stylesheet" type="text/css">

<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0"  style="padding-left: 20px;border-bottom:1.8px solid #ebebeb; " >
  <tr>
<?php
	
	if (isset($_GET['excel']) && ($_GET['excel']=='excel')) {
?>
	<td colspan="7" width="100%" align="center">PLATAFORMA DIGITAL GEORREFERENCIADA GEO PERÚ</td>
<?php
	} else {
?>
<td   align="left"><img class="logosegdi" src="imajes/gob.pe.svg" ></td>

<td  align="right"><img class="logosayhuite" src="imajes/geoperu.png" ></td>
<?php
	}
?>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" style="padding-left: 20px; padding-right: 20px">
  <tr>
	<td>
	<table width="100%" cellspacing="0" cellpadding="1" class="marco_reporte">
	  <tr>
		<td>
		<table width="100%" cellpadding="10" cellspacing="0">
		  <tr>
			<td colspan="7" class="titulo_reporte" style="font-size:20px;"><?php echo $titulo;?></td>
		  </tr>
		</table>
	  </td></tr>
	</table>
  </td></tr>
</table>
