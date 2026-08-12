<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Graficos Consolidado</title> 
</head>

<body>

<?php

		   $pTit1 = $_GET['oTit1'];	   
		   $pTit2 = $_GET['oTit2'];
		   $pS0 = $_GET['oS0'];
		   $pS1 = $_GET['oS1'];
		   $pS2 = $_GET['oS2'];
		   $pS3 = $_GET['oS3'];
		   $pS4 = $_GET['oS4'];
		   $pS5 = $_GET['oS5'];
		   $pDec = $_GET['oDec'];
		   
//		   echo  $pTit1."</BR>";
//		   echo  $pTit2."</BR>";
//		   echo  $pS1."</BR>";

		   $arrEstados = explode(",", $pS0);
		   $arrS1 = explode(",", $pS1);
		   $arrS2 = explode(",", $pS2);
		   $arrS3 = explode(",", $pS3);
		   $arrS4 = explode(",", $pS4);
		   $arrS5 = explode(",", $pS5);

// Standard inclusions   
	 include("pChart/pData.class");
	 include("pChart/pChart.class");

 // Dataset definition 
	 $DataSet = new pData;
//	 $DataSet->AddPoint(array(13,3,6),"Serie1");
//	 $DataSet->AddPoint(array(3,3,5),"Serie2");
//	 $DataSet->AddPoint(array(4,8,14),"Serie3");
//	 $DataSet->AddPoint(array(7,4,9),"Serie4");
//	 $DataSet->AddPoint(array(3,6,5),"Serie5");

	 $DataSet->AddPoint( $arrS1 ,"Serie1");
	 $DataSet->AddPoint( $arrS2 ,"Serie2");
	 $DataSet->AddPoint( $arrS3 ,"Serie3");
	 $DataSet->AddPoint( $arrS4 ,"Serie4");
	 $DataSet->AddPoint( $arrS5 ,"Serie5");

	 //$DataSet->AddPoint(array("EDUCACION","SALUD","TRANSPORTE"),"Serie6");
	 $DataSet->AddPoint( $arrEstados , "Serie6" );
	 
	 $DataSet->AddAllSeries();
	 
	 $DataSet->SetAbsciseLabelSerie("Serie6");
 
 //$DataSet->SetAbsciseLabelSerie(); 
	 $DataSet->SetSerieName("Planificado","Serie1");
	 $DataSet->SetSerieName("Perfil","Serie2");
	 $DataSet->SetSerieName("Estudio","Serie3");
	 $DataSet->SetSerieName("En Ejecucion","Serie4");
	 $DataSet->SetSerieName("Ejecutado","Serie5");

 // Initialise the graph
	 $Test = new pChart(1000,430);
	 $Test->setFontProperties("Fonts/tahoma.ttf",8);
	
	 $DataSet->RemoveSerie("Serie6");
	
	 $Test->setGraphArea(100,30,780,360);                            //Area del Grafico 	 
	 $Test->drawFilledRoundedRectangle(7,7,893,423,5,240,240,240);  // Color de Fondo	 
	 $Test->drawRoundedRectangle(5,5,895,425,5,255,0,0);            // Borde del Area del Grafico
	 
	 $Test->drawGraphArea(255,255,255,TRUE);
//	 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2,TRUE);
	 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,0,TRUE);
	 $Test->drawGrid(4,TRUE,230,230,230,50);    // Malla de fondo
	
 // Draw the 0 line
	 $Test->setFontProperties("Fonts/tahoma.ttf",6);
	 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);

 // Draw the bar graph
	 $Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE,80);

 // Set labels
	 $Test->setFontProperties("Fonts/tahoma.ttf",8);		 
	 for ( $i = 0 ; $i < count($arrEstados) ; $i ++) {	 
	     if ($arrS1[$i]!=0) {
 	        $Test->setLabelNroCol($DataSet->GetData(),$DataSet->GetDataDescription(),"Planificado", $arrEstados[$i],$arrS1[$i],5,1,121,255,174,$pDec);
		 }	
	     if ($arrS2[$i]!=0) {
	        $Test->setLabelNroCol($DataSet->GetData(),$DataSet->GetDataDescription(),"Perfil", $arrEstados[$i],$arrS2[$i],5,2,121,255,174,$pDec);
		 }	
	     if ($arrS3[$i]!=0) {
            $Test->setLabelNroCol($DataSet->GetData(),$DataSet->GetDataDescription(),"Estudio", $arrEstados[$i],$arrS3[$i],5,3,121,255,174,$pDec);
		 }	
	     if ($arrS4[$i]!=0) {
            $Test->setLabelNroCol($DataSet->GetData(),$DataSet->GetDataDescription(),"En Ejecucion", $arrEstados[$i],$arrS4[$i],5,4,121,255,174,$pDec);
		 }	
	     if ($arrS5[$i]!=0) {
            $Test->setLabelNroCol($DataSet->GetData(),$DataSet->GetDataDescription(),"Ejecutado", $arrEstados[$i],$arrS5[$i],5,5,121,255,174,$pDec);
		 }	
	 }

 
     // LEYENDA
	 $Test->setFontProperties("Fonts/tahoma.ttf",8);
	 $Test->drawLegend(900,40,$DataSet->GetDataDescription(),255,255,255);   //LEYENDA
	 
	 // TITULO
	 $Test->setFontProperties("Fonts/tahoma.ttf",12);
	 $Test->drawTitle(50,22, $pTit1 ,50,50,50,885);
	
	 // TITULO2
	 $Test->setFontProperties("Fonts/tahoma.ttf",8);
	 $Test->drawTitle(10,22, $pTit2 ,50,50,50);
	
	 $sFile= "tmp/lalo1.png";
	 $Test->Render($sFile);
		 
	 print "<img src='" . $sFile . "' width='900' height='430' />";		   

?>

</body>
</html>


