<?php
function limpiarCadena($valor) //validar y evitar inyecciones
{
	$valor = str_ireplace("SELECT","",$valor);
	$valor = str_ireplace("COPY","",$valor);
	$valor = str_ireplace("UPDATE","",$valor);
	$valor = str_ireplace("DELETE","",$valor);
	$valor = str_ireplace("DROP","",$valor);
	$valor = str_ireplace("DUMP","",$valor);
	$valor = str_ireplace(" OR ","",$valor);
	$valor = str_ireplace("%","",$valor);
	$valor = str_ireplace("LIKE","",$valor);
	$valor = str_ireplace("--","",$valor);
	$valor = str_ireplace("^","",$valor);
	$valor = str_ireplace("[","",$valor);
	$valor = str_ireplace("]","",$valor);
	$valor = str_ireplace("\\","",$valor);
	$valor = str_ireplace("!","",$valor);
	$valor = str_ireplace("�","",$valor);
	$valor = str_ireplace("?","",$valor);
	$valor = str_ireplace("=","",$valor);
	$valor = str_ireplace("&","",$valor);
	$valor = str_ireplace("'","",$valor);
	$valor = str_ireplace("<","",$valor);
	$valor = str_ireplace(">","",$valor);
	$valor = str_ireplace("#","",$valor);
	$valor = str_ireplace("chr","",$valor);
	$valor = str_ireplace(")","",$valor);
	$valor = str_ireplace("(","",$valor);
	
	return $valor;
}

function limpiarNumeros($valor) // valida que solo sean numeros
  {
	$valor = preg_replace('/[^0-9]/', '', $valor);
	return $valor;
  }
 
function check_email_address($email) 
{
	// Primero, checamos que solo haya un s�mbolo @, y que los largos sean correctos
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) 
	{
		// correo inv�lido por n�mero incorrecto de caracteres en una parte, o n�mero incorrecto de s�mbolos @
    return false;
  }
  // se divide en partes para hacerlo m�s sencillo
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) 
	{
    //if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) 
	if (!ereg("^(([A-Za-z0-9!_-][A-Za-z0-9!_.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) 
		{
      return false;
    }
  } 
  // se revisa si el dominio es una IP. Si no, debe ser un nombre de dominio v�lido
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) 
	{ 
     $domain_array = explode(".", $email_array[1]);
     if (sizeof($domain_array) < 2) 
		 {
        return false; // No son suficientes partes o secciones para se un dominio
     }
     for ($i = 0; $i < sizeof($domain_array); $i++) 
		 {
        if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) 
				{
           return false;
        }
     }
  }
  return true;
}

function getTablaCampo($tabla,$campo){
		$linkPG = Conectarse_POSTGRES(); 	
		$result = pg_query($linkPG," SELECT * from def_dic_tabla where upper(nom_tabla) = upper('".$tabla."') and upper(nom_campo) = upper('".$campo."');");
		$nom_cam_reporte = pg_fetch_result($result,0,"\"nom_cam_reporte\"");
		if($nom_cam_reporte!=''){
			$mensaje=$nom_cam_reporte;
		}else{
			$mensaje='</br><b>�--------- '.$campo.' ---------!</b></p></br>';
		}
		return $mensaje;
		pg_free_result($result); 
		pg_close($linkPG); 
	}	

	function  getComDesc($player){
	$linkPG = Conectarse_POSTGRES();
	$conExiste=pg_query($linkPG,"select i.est_descarga from def_capa_informacion i left join def_capa c on  i.idcapa=c.idcapa where c.capaxml  = '".$player."' and i.est_descarga=1;");
	if(pg_num_rows($conExiste)>0){
		$result = pg_query($linkPG," select d.nom_tabla as nom_tabla from def_dic_tabla d left join def_capa c on c.nom_tabla = d.nom_tabla where c.capaxml = '".$player."'");
		$numRows=pg_num_rows($result);
		if($numRows>0){
			$nom_tabla = pg_fetch_result($result,0,"\"nom_tabla\"");
		}else {
			$nom_tabla='';
		}
		return $nom_tabla;
	}else{
		return '';
	}
}
 function getExisteCampo($niv_descarga,$nom_tabla){
    	$linkPG = Conectarse_POSTGRES(); 	
       	$consulta_campos= pg_query($linkPG,"SELECT COLUMN_NAME as nombre_campo,DATA_TYPE as tipo_campo 
                                           FROM information_schema.COLUMNS 
                                          WHERE TABLE_NAME = '".$nom_tabla."';");
        $rowsc = pg_num_rows($consulta_campos);
       // echo('$niv_descarga= '.$niv_descarga.'<br>$nom_tabla='.$nom_tabla.'</br>$rowsc = '.$rowsc.'</br>');
        $u=0;
        $codcadena='';
		while($u<$rowsc){
			if($niv_descarga==1){//Para descarga nacional
	           if(pg_fetch_result($consulta_campos,$u,"\"nombre_campo\"")=='cod_dpto'){
					$codcadena      = $codcadena.'cod_dpto';
				}else{
					$codcadena=$codcadena;
				}
			}elseif($niv_descarga==2){//Para descarga Departamental
				if(pg_fetch_result($consulta_campos,$u,"\"nombre_campo\"")=='cod_dpto'){
					$codcadena=$codcadena.'cod_dpto';
				}elseif(pg_fetch_result($consulta_campos,$u,"\"nombre_campo\"")=='cod_prov'){
					$codcadena=$codcadena.'cod_prov';
				}elseif(pg_fetch_result($consulta_campos,$u,"\"nombre_campo\"")=='cod_dist'){
					$codcadena=$codcadena.'cod_dist';
				}
			}elseif($niv_descarga==3){//Para descarga Provincial
			    if(pg_fetch_result($consulta_campos,$u,"\"nombre_campo\"")=='cod_prov'){
					$codcadena=$codcadena.'cod_prov';
				}elseif(pg_fetch_result($consulta_campos,$u,"\"nombre_campo\"")=='cod_dist'){
					$codcadena=$codcadena.'cod_dist';
				}
			}elseif($niv_descarga=='4'){//Para descarga Distrial
	           if(pg_fetch_result($consulta_campos,$u,"\"nombre_campo\"")=='cod_dist'){
					$codcadena=$codcadena.'cod_dist';
			    }
			}
			$u++;
	    }
	   return $codcadena;

    }
?>
