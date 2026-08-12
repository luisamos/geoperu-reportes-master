<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Graficos Consolidado</title> 
</head>

<body>

	<?php   	
	 // Inludes
    
	 include("pChart/pData.class");
	 include("pChart/pChart.class");
	
	 // Dataset definition 
	 $DataSet = new pData;
	 //$DataSet->ImportFromCSV("Sample/CO2.csv",",",array(1,2,3,4),TRUE,0);
 $DataSet->AddPoint(array(1,4,2,6,3),"Serie1");
 $DataSet->AddPoint(array(5,3,7,2,5),"Serie2");
 $DataSet->AddPoint(array(5,3,2,9,3),"Serie3");
 $DataSet->AddPoint(array("Planificado","Perfil","Estudio","En ejecucion", "Ejecutado"),"Serie4");

 $DataSet->AddAllSeries();
	 
 $DataSet->SetAbsciseLabelSerie("Serie4");
 
//	 $DataSet->SetAbsciseLabelSerie();
	 
 $DataSet->SetSerieName("SALUD","Serie1");
 $DataSet->SetSerieName("EDUCACION","Serie2");
 $DataSet->SetSerieName("TRANSPORTE","Serie3");

 $DataSet->SetYAxisName("SOLES");
 $DataSet->SetXAxisName("PROYECTOS - ESTADO");
	
	 // Initialise the graph
	 //700 . 230
	 $Test = new pChart(900,430);
	 $Test->reportWarnings("GD");
	 $Test->setFontProperties("Fonts/tahoma.ttf",8);
	 $Test->setGraphArea(60,30,680,360);
	 
	 $Test->drawFilledRoundedRectangle(7,7,893,423,5,240,240,240);
	 
	 $Test->drawRoundedRectangle(5,5,895,425,5,230,230,230);
	 
 $DataSet->RemoveSerie("Serie4");
 
	 $Test->drawGraphArea(255,255,255,TRUE);
	 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,90,2);
	 $Test->drawGrid(4,TRUE,230,230,230,50);
	
	 // Draw the 0 line
	 $Test->setFontProperties("Fonts/tahoma.ttf",6);
	 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);
	
	 // Draw the line graph
	 $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
	 $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
	
	 // Finish the graph
	 $Test->setFontProperties("Fonts/tahoma.ttf",8);   
	 $Test->drawLegend(700,40,$DataSet->GetDataDescription(),255,255,255);   
	 $Test->setFontProperties("Fonts/tahoma.ttf",10);
	 $Test->drawTitle(60,22,"PROYECTOS: SECTOR vs ESTADOS",50,50,50,585);

	 
	 $sFile= "tmp/lalo1.png";
	 
	 $Test->Render($sFile);
	 
     print "<img src='" . $sFile . "' width='900' height='430' />";
	 
     //unlink($sFile);
	?>

</body>
</html>


