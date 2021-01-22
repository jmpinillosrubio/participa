<?php


echo '<html>
	  <head>
		<meta charset="utf-8" />
		<title>Resultado de la votación</title>
		<link href="css.css" type="text/css" rel="stylesheet" />

	  </head>
	  <body>
	<section>
		<div class="container">
		<div class="user signinBx">
		<div class="imgBx">
			<img src="https://participa.fespugtclm.es/participa.png?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60">
		</div>    
		<div class="formBx">
		  <form action="ee.php" method="post">
			  <h2>Resultados de la votación</h2>
			<input type="hidden" name="dni" value='.$_REQUEST[dni].' /><p>
			<input type="hidden" name="control" value=1 /><p>';
	
	echo '<table class="default">';
	echo '<tr><td>&nbsp;</td></tr>';
$f_respuestas = fopen("respuestas.txt", "r") or exit("Error abriendo fichero listado telefonos!");
	$num_respuestas=0;
	
	while ($linea = fgets($f_respuestas)) {	
		$array_respuestas[$num_respuestas]=$linea;
		$num_respuestas++;
		
	}		
fclose($f_respuestas);

$f_votos = fopen("votos.txt", "r") or exit("Error abriendo fichero listado telefonos!");

$i=0;

while ($linea = fgets($f_votos)) {	
	$array_votos[$i]=(int) $linea;
	
	$i++;
	}	
fclose($f_votos);

$totalvotos=$i+1;

$i=1;

while ($i<=$num_respuestas)
	{
	$j=0;
	while ($j<=$totalvotos)
		{
		if ($array_votos[$j]==$i){ 
			$votos[$i]++;
		}
	$j++;
	}
	echo '<tr>';
	echo '<td><b>'.$votos[$i]."</b></td><td>&nbsp;</td><td>".$array_respuestas[$i-1]."</td></tr><tr><td>&nbsp;</td></tr>";
	$i++;
	}
echo "</table>";
	
	
	