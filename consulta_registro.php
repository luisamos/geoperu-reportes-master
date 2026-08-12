<?php
/***************************************************************************************
Organizaci�n: 	Presidencia del Consejo de Ministros - PCM
Gerencia	: 	Secretar�a de Descentralizaci�n
Sistema		: 	Sistema Nacional de Informaci�n Geogr�fica - SAYWITE
Coordinador/Desarrollador TIC: Vitaly Uriel Cespedes Reynaga vitalyuri@hotmail.com/vitalyuri@gmail.com/969228151
Periodo		: 	Septiembre 2012 - 2013
***************************************************************************************/

	include_once('conexion_postgres.phtml');
	$link = Conectarse_POSTGRES();
	$cod_evento = "1";
	$vacantes	= 231;
	$nom_evento = "Curso Virtual: Uso aplicativo del SIG Sayhuite";
	$anuncion   = 'Estimado usuario le comunicamos que las vacantes para el curso virtual '.$nom_evento.', ya fueron cubiertas.<br>Sin embargo, puede  ser considerado en el segundo grupo de estudios del curso virtual que iniciar&aacute; proximamente.</br> S&iacute; est&aacute; de acuerdo, puede continuar con el registro de sus datos <a  href="consulta_registro2.php?id=2"> </a>';
	$consultass = "select count(*) as numero  from mat_registrosnuevo where cod_evento ='".$cod_evento."';"; 
	$rsDet = pg_query( $link , $consultass );
	$numero = pg_fetch_result($rsDet,0,"numero");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title> Registro Sayhuite</title>

<script  type="text/javascript">
window.onload = function() { 
	disablefield1();
	disablefield2();
}

	function disablefield1(){
	 if (document.getElementById('yes_radiocur').checked == 1){
		 document.getElementById('cursovirtualinst').disabled='';
		 document.getElementById('cursovirtualinst').value=''; 
		 document.getElementById('cursovirtualano').disabled='';
		 document.getElementById('cursovirtualano').value=''; 
		 document.getElementById('cursopreseninst').disabled='';
		 document.getElementById('cursopreseninst').value=''; 
		 document.getElementById('cursopresenano').disabled='';
		 document.getElementById('cursopresenano').value=''; 
	 }else{
		 document.getElementById('cursovirtualinst').disabled='disabled';
		 document.getElementById('cursovirtualinst').value=''; 
		 document.getElementById('cursovirtualano').disabled='disabled';
		 document.getElementById('cursovirtualano').value=''; 
		 document.getElementById('cursopreseninst').disabled='disabled';
		 document.getElementById('cursopreseninst').value=''; 
		 document.getElementById('cursopresenano').disabled='disabled';
		 document.getElementById('cursopresenano').value=''; } 
	 } 
	 
	function disablefield2(){
	 if (document.getElementById('yes_radioorg').checked == 1){
		 document.getElementById('cargoorganizacion').disabled='';
		 document.getElementById('cargoorganizacion').value='';
	 }else{
		 document.getElementById('cargoorganizacion').disabled='disabled';
		 document.getElementById('cargoorganizacion').value=''; } 		 
	 } 
</script>

</head>


<script type="text/javascript" src="../../usuarios/js/general.js"></script>
<body>
<form action="save.php" method="post" name="form" id="frmmantusuarios" onSubmit="return seguimiento(form);">
<style type="text/css">
.tabla1{
	font-family: Verdana, Arial, Helvetica, sans-serif;
	border: 1px solid #ccc;
	border-collapse: collapse;
	border-spacing: 0px;
	padding: 1px;
	margin-top: 4px;
	margin-bottom: 4px;
}
.tabla2{
	font-family: Arial, Helvetica, sans-serif;
	font-size:12px;
	border-collapse: collapse;
	padding: 5px;
}
.lista_titulo{
	text-align: left;
	font-weight: normal;
	background-color: #ffffff;
	border: 1px solid white;
	padding: 5px;
	width:40%
}
.lista_titulo_principal{
	text-align: center;
	font-weight: normal;
	background-color: #125565;
	color:#ffffff;
	border: 1px solid white;
	padding: 10px;
	font-size:14px;
	border-collapse: collapse;
}
.lista_titulo_centro{
	text-align: center;
	font-weight: normal;
	background-color: #ffffff;	
	border: 1px solid white;
	padding: 10px;
	font-size:14px;
	border-collapse: collapse;
}
.lista_texto{
	text-align: left;
	font-weight: normal;
	background-color: #fff;
	border: 1px solid #acacac;
	padding-left: 3px;
}
.ml-button-12 {
	background-color: #F39C45;
	border: 1px solid #A87017;
	-moz-box-shadow:inset 0px 0px 1px rgba(184,129,39,1);
	-webkit-box-shadow:inset 0px 0px 1px rgba(184,129,39,1);
	box-shadow:inset 0px 0px 1px rgba(184,129,39,1);
	background-image: -o-linear-gradient(90deg , rgb(250,153,60) 0%, rgb(244,197,140) 100%);
	background-image: -moz-linear-gradient(90deg , rgb(250,153,60) 0%, rgb(244,197,140) 100%);
	background-image: -webkit-linear-gradient(90deg , rgb(250,153,60) 0%, rgb(244,197,140) 100%);
	background-image: -ms-linear-gradient(90deg , rgb(250,153,60) 0%, rgb(244,197,140) 100%);
	background-image: linear-gradient(90deg , rgb(250,153,60) 0%, rgb(244,197,140) 100%);
	color: #A8580B;
	text-shadow: rgba(254,252,252,0.5) 0px 1px 0px;
}

/*Hover*/
.ml-button-12:hover {
	background-color: #EAB26C;
	background-image: -o-linear-gradient(90deg , rgb(248,169,91) 0%, rgb(244,210,170) 100%);
	background-image: -moz-linear-gradient(90deg , rgb(248,169,91) 0%, rgb(244,210,170) 100%);
	background-image: -webkit-linear-gradient(90deg , rgb(248,169,91) 0%, rgb(244,210,170) 100%);
	background-image: -ms-linear-gradient(90deg , rgb(248,169,91) 0%, rgb(244,210,170) 100%);
	background-image: linear-gradient(90deg , rgb(248,169,91) 0%, rgb(244,210,170) 100%);
}

/*Active*/
.ml-button-12:active {
	background-color: #E77E21;
	-moz-box-shadow:inset 0px 0px 5px rgba(184,129,39,1);
	-webkit-box-shadow:inset 0px 0px 5px rgba(184,129,39,1);
	box-shadow:inset 0px 0px 5px rgba(184,129,39,1);
	background-image: -o-linear-gradient(90deg , rgb(244,197,140) 0%, rgb(250,153,60) 100%);
	background-image: -moz-linear-gradient(90deg , rgb(244,197,140) 0%, rgb(250,153,60) 100%);
	background-image: -webkit-linear-gradient(90deg , rgb(244,197,140) 0%, rgb(250,153,60) 100%);
	background-image: -ms-linear-gradient(90deg , rgb(244,197,140) 0%, rgb(250,153,60) 100%);
	background-image: linear-gradient(90deg , rgb(244,197,140) 0%, rgb(250,153,60) 100%);
	text-shadow: none;
}
</style>


<table width="60%" align="center" class="tabla1">
<tr><td colspan = "2" align="center" class="lista_titulo_principal"><?php if($numero<=$vacantes){ echo($nom_evento);}else {echo($anuncion);}?></td></tr>
  
	  
<?php	  if($numero<=$vacantes){
	echo('
	<tr>
		<td  class="lista_titulo">Ficha de inscripci&oacute;n</td>
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td>I. DATOS PERSONALES</td>
	</tr>
	<tr>
	<td>
	<table width="100%" align="center" class="tabla2">	 
		<tr>
			<td class="lista_titulo">Nombres :</td>
			<td class="lista_texto">
			<input type="text" name="snombres" id="snombres"  class="CajaTextoObli" onKeyPress="javascript:convertirMayusculas();" style="text-transform:uppercase;" maxlength="30">
			<input name="cod_evento"  type="hidden" size="100" maxlength="100"  value="'.$cod_evento.'" class="CajaTextoObli" readonly="readonly" />
			<input name="nom_evento"  type="hidden" size="200" maxlength="200"  value="'.$nom_evento.'" class="CajaTextoObli" readonly="readonly" />
			<input type="hidden" name="tipocurso" name="tipocurso" id="tipocurso" value="a"/>
			</td>
		</tr>
		<tr>
			<td class="lista_titulo">Apellido Paterno :</td>
			<td class="lista_texto"><input type="text" name="sappaterno" id="sappaterno"  class="CajaTextoObli" style="text-transform:uppercase;" maxlength="30"></td>
	    </tr>
		<tr>
			<td class="lista_titulo">Apellido Materno :</td>
			<td class="lista_texto"><input type="text" name="sapmaterno" id="sapmaterno"  class="CajaTextoObli" style="text-transform:uppercase;" maxlength="30"></td>
		</tr>
		<tr>
			<td class="lista_titulo">Doc. Identidad :</td>
			<td class="lista_texto"><select name="tipo_di" id="tipo_di" >
				<option value="DNI">DNI</option>
				<!--option value="CE">CE</option>
				<option value="Otro">Otro</option--></select>
			&nbsp;&nbsp;<input type="text" name="di" id="di" value="" class="CajaTextoObli"  maxlength="8">
			</td>
		</tr>
		<tr>
			<td class="lista_titulo">Sexo :</td>
			<td class="lista_texto">
				<input type="radio" name="sexo" value="M" checked >Masculino
				<input type="radio" name="sexo" value="F" >Femenino				
			</td>
		</tr>
		<tr>
			<td class="lista_titulo">Regi&oacute;n:</td>
			<td class="lista_texto"><input type="text" name="departamento" id="departamento"  class="CajaTextoObli" maxlength="20"></td>
		</tr>
		<tr>
			<td class="lista_titulo">Provincia:</td>
			<td class="lista_texto"><input type="text" name="provincia" id="provincia"  class="CajaTextoObli" maxlength="20"></td>
		</tr>
		<tr>
			<td class="lista_titulo">Distrito:</td>
			<td class="lista_texto"><input type="text" name="distrito" id="distrito" class="CajaTextoObli" maxlength="20"></td>
		</tr>
		<tr>
			<td class="lista_titulo">Direcci&oacute;n donde reside:</td>
			<td class="lista_texto">
				<textarea name="direccion" id="direccion" cols="40" class="CajaTextoObli" style="text-transform:uppercase;"></textarea></td>
		</tr>
		<tr>
			<td class="lista_titulo">Tel&eacute;fono :</td>
			<td class="lista_texto"><input type="text" name="telefono" id="telefono" class="CajaTextoObli" onKeypress="soloNumeros(event,0);" maxlength="9"></td>
		</tr>
		<tr>
			<td class="lista_titulo">Celular :</td>
			<td class="lista_texto"><input type="text" name="celular" id="celular"  class="CajaTextoObli" onKeypress="soloNumeros(event,0);"maxlength="9"></td>
		</tr>
		<tr>
			<td class="lista_titulo">Correo electr&oacute;nico personal:</td>
			<td class="lista_texto"><input type="text" name="selctmail2" id="selctmail2"  class="CajaTextoObli"></td>
		</tr>
		<tr>
			<td class="lista_titulo">Correo institucional:</td>
			<td class="lista_texto"><input type="text" name="selctmail" id="selctmail"  class="CajaTextoObli"></td>
		</tr>		
		<tr>
			<td class="lista_titulo">Profesi&oacute;n / Ocupaci&oacute;n:</td>
			<td class="lista_texto"><input type="text" name="profesion" id="profesion"  class="CajaTextoObli"></td>
		</tr>	
	</table>
	</td>
    </tr>
  
    <tr>
		<td></td>
	</tr>
	<tr>
		<td>II. DATOS LABORALES</td>
	</tr>
	<tr>
	<td>
	<table width="100%" align="center" class="tabla2">	 		
		<tr>
			<td class="lista_titulo">Nombre de la Instituci&oacute;n donde labora :</td>
			<td class="lista_texto"><input type="text" name="ntrabajo" id="ntrabajo"  class="CajaTextoObli" style="text-transform:uppercase;" maxlength="30"></td>
	    </tr>
		<tr>
			<td class="lista_titulo">&Aacute;rea laboral :</td>
			<td class="lista_texto"><input type="text" name="alaboral" id="alaboral"  class="CajaTextoObli" style="text-transform:uppercase;" maxlength="30"></td>
		</tr>
		<tr>
			<td class="lista_titulo">Direcci&oacute;n de su centro de trabajo :</td>
			<td class="lista_texto">
				<textarea name="dtrabajo" id="dtrabajo" cols="40" class="CajaTextoObli" style="text-transform:uppercase;"></textarea></td>
		</tr>
		<tr>
			<td class="lista_titulo">Cargo :</td>
			<td class="lista_texto"><input type="text" name="cargo" id="cargo" class="CajaTextoObli"></td>
		</tr>
		<tr>
			<td class="lista_titulo">Tel&eacute;fono :</td>
			<td class="lista_texto"><input type="text" name="telefonotrabajo" id="telefonotrabajo" class="CajaTextoObli" onKeypress="soloNumeros(event,0);" maxlength="9"></td>
		</tr>
		<tr>
			<td class="lista_titulo">Anexo :</td>
			<td class="lista_texto"><input type="text" name="anexo" id="anexo" class="CajaTextoObli" onKeypress="soloNumeros(event,0);" maxlength="6"></td>
		</tr>
	</table>
	</td>
    </tr>
  
    <tr>
		<td></td>
	</tr>
	<tr>
		<td>III. FORMACION</td>
	</tr>
	<tr>
		<td>
		<table width="100%" align="center" class="tabla2">	 			
			<tr>
				<td class="lista_titulo">Nivel Educativo :</td>
				<td class="lista_texto"><input type="text" name="neducativo" id="neducativo"  class="CajaTextoObli" style="text-transform:uppercase;" maxlength="30"></td>
			</tr>
			<tr>
				<td class="lista_titulo">Especialidad :</td>
				<td class="lista_texto"><input type="text" name="especialidad" id="especialidad" class="CajaTextoObli"></td>
			</tr>
			<tr>
				<td class="lista_titulo">Año que culmin&oacute; sus estudios :</td>
				<td class="lista_texto"><input type="text" name="anoestculminado" id="anoestculminado" class="CajaTextoObli" onKeypress="soloNumeros(event,0);" maxlength="4"></td>
			</tr>
		</table>
		</td>
    </tr>
	<tr>
	<td>
	<table width="100%" align="center" class="tabla2">	
		<tr>
			<td class="lista_titulo">Ha participado en cursos sobre Sistemas de Informaci&oacute;n Geogr&aacute;fica? :</td>
			<td class="lista_texto">
				<input type="radio" name="cursogis" id="yes_radiocur" value="Y" onChange="disablefield1();">Si
				<input type="radio" name="cursogis" id="no_radiocur" value="N" onChange="disablefield1();" checked>No				
			</td>
		</tr>
	</table>
	</td>
		</tr>
	<tr>
		<td>
		<table width="100%" align="center" class="tabla2">	 
			<tr>
				<td class="lista_titulo_centro">Modalidad :</td>
				<td class="lista_titulo_centro">Instituci&oacute;n :</td>
				<td class="lista_titulo_centro">Año :</td>
			</tr>
			<tr>
				<td class="lista_titulo">Virtual :</td>
				<td class="lista_texto"><input type="text" name="cursovirtualinst" id="cursovirtualinst" class="CajaTextoObli" ></td>
				<td class="lista_texto"><input type="text" name="cursovirtualano" id="cursovirtualano" class="CajaTextoObli" onKeypress="soloNumeros(event,0);" maxlength="4"></td>
			</tr>
			<tr>
				<td class="lista_titulo">Presencial :</td>
				<td class="lista_texto"><input type="text" name="cursopreseninst" id="cursopreseninst" class="CajaTextoObli"></td>
				<td class="lista_texto"><input type="text" name="cursopresenano" id="cursopresenano" class="CajaTextoObli" onKeypress="soloNumeros(event,0);" maxlength="4"></td>
			</tr>
		</table>
		</td>
	</tr>
  
    <tr>
		<td></td>
	</tr>
	<tr>
		<td>IV. COMUNIDAD</td>
	</tr>
	<tr>
	<td>
	<table width="100%" align="center" class="tabla2">	 		
		<tr>
			<td class="lista_titulo">Pertenece a alguna organizaci&oacute;n en su comunidad o lugar de residencia? :</td>
			<td class="lista_texto">
				<input type="radio" name="isorganizacion" value="Y" id="yes_radioorg" onChange="disablefield2();" >Si
				<input type="radio" name="isorganizacion" value="N" id="no_radioorg" onChange="disablefield2();" checked >No				
			</td>
		</tr>
	</table>
	</td>
	</tr>
	<tr>
		<td>
		<table width="100%" align="center" class="tabla2">	 			
			<tr>
				<td class="lista_titulo">Cargo :</td>
				<td class="lista_texto"><input type="text" name="cargoorganizacion" id="cargoorganizacion" class="CajaTextoObli"></td>				
			</tr>			
		</table>
		</td>
	</tr>
  
    <tr>
		<td></td>
	</tr>
	<tr>
		<td>V. USO DE INTERNET</td>
	</tr>	
	<tr>
	<td>
	<table width="100%" align="center" class="tabla2">	 		
		<tr>
			<td class="lista_titulo">¿Con que frecuencia usa internet? :</td>
		</tr>
		<tr>
			<td class="lista_titulo">Todos los dias :</td>
			<td class="lista_texto"><input type="radio" name="frecuenciainternet" value="5" checked></td>
	    </tr>
		<tr>
			<td class="lista_titulo">Tres veces a la semana :</td>
			<td class="lista_texto"><input type="radio" name="frecuenciainternet" value="3"></td>
		</tr>		
		<tr>
			<td class="lista_titulo">Una vez a la semana :</td>
			<td class="lista_texto"><input type="radio" name="frecuenciainternet" value="1"></td>
		</tr>	
	</table>
	</td>
    </tr>
	
  ');
		}
?>
</table>
<br>
<table align="center"> 
<tr>
	<td>
		  <label>
		    <input name="submit" type="submit" class="ml-button-12"  value="Grabar" >
          </label>
	</td>
</tr>
	
</table>
</form>
</body>
</html>