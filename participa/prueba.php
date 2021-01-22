<?php

$i=0;
$dni_votos = fopen("dni_votos.txt", "r") or exit("Error abriendo fichero listado dni votos!");       
while ($linea = fgets($dni_votos)) {
	$array_lineas[$i]=$linea;
	$i++;
	echo $linea;
	echo "<br>";
}

shuffle($array_lineas);
        
$dni_votos = fopen("dni_votos.txt", "w") or exit("Error abriendo fichero listado dni votos!");

if (flock($dni_votos, LOCK_EX | LOCK_NB)) {
	while ($i>=0) {
		echo $array_lineas[$i];
		echo "<br>";
		fwrite($dni_votos, $array_lineas[$i]);
		$i--;
	}
	flock($dni_votos, LOCK_UN);
}







echo "ok";
		 
?>