<?php
$voto=$_REQUEST[voto]."\r\n";
$dni=$_REQUEST[dni];

$control_voto=false;

//La siguiente parte controla que el voto no se encuentre ya en el sistema para evitar que pueda volver a votar utilizando el botón de atrás del navegador. 
$dni_votos = fopen("dni_votos.txt", "r") or exit("Error abriendo fichero listado dni votos!");
while ($linea = fgets($dni_votos)) {

    if (md5($_REQUEST[dni]) == substr($linea, 0, 32)) {
    	$control_voto = true; 
		
	}
    $numlinea++;
}
fclose($dni_votos);


if ($control_voto==false){ //Si el voto no se encuentra registrado, inicia el proceso de grabación del voto. 

$fp = fopen("votos.txt", "a"); //Almacena el voto
    if (flock($fp, LOCK_EX | LOCK_NB)) {
		fwrite($fp, $voto);
        //sleep(10);
        flock($fp, LOCK_UN);
    } 
	else {
        print "Fichero bloqueado";
    }
$fp = fopen("dni_votos.txt", "a"); //Almacena el Hash MD5 de la persona que vota
    if (flock($fp, LOCK_EX | LOCK_NB)) {
		fwrite($fp, md5($dni)."\r\n");
        //sleep(10);
        flock($fp, LOCK_UN);
    } 
	else {
        print "Fichero bloqueado";
    }

//Las siguientes sentencias reordenan los Hash en el fichero para evitar que se almacenen en el mismo orden que los votos, de esta forma se garantiza la imposibilidad de asociar el Hash del DNI con ninguno de los votos. 	
$i=0;
$dni_votos = fopen("dni_votos.txt", "r") or exit("Error abriendo fichero listado dni votos!");       
while ($linea = fgets($dni_votos)) {
	$array_lineas[$i]=$linea; //Almacena la lista de Hash del fichero dni_votos.txt en un array
	$i++;
	
}

shuffle($array_lineas); //Mezcla el array de Hash
        
$dni_votos = fopen("dni_votos.txt", "w") or exit("Error abriendo fichero listado dni votos!");

if (flock($dni_votos, LOCK_EX | LOCK_NB)) {
	while ($i>=0) {
		
		fwrite($dni_votos, $array_lineas[$i]); //Guarda el array de Hash de nuevo en el fichero con un orden aleatorio
		$i--;
	}
	flock($dni_votos, LOCK_UN);
}

// Las siguientes líneas muestran la página web con la confirmación del voto y el Hash asignado al voto para futuras verificaciones
echo '<html>
  <head>
	<meta charset="utf-8" />
    <title>Título general</title>
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
      <form action="https://fespugtclm.es" method="post">
        <h2>Voto registrado correctamente</h2>	
        <input type="submit" name="" value="Salir">
        <p class="signup">Tu voto ha quedado registrado en el sistema con el identificador: <br><b>'.md5($dni).'</b></p>
        </form>
    </div>
    </div>
        
    
     <div class="imgBx">
        <img src="https://images.unsplash.com/photo-1481824429379-07aa5e5b0739?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60">
    </div>
    </div>
    
</section>
  </body>
</html>';
}

// En caso de que se detecte que ya había votado, solo se puede dar esta circunstancia usando el botón atrás del navegador, muestra el error en la página y no registra el voto. 
else {
	echo '<html>
  <head>
	<meta charset="utf-8" />
    <title>Título general</title>
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
      <form action="index.html" method="post">
          <h2>Voto ya realizado</h2>
       
        <p class="signup">El voto ha sido rechazado porque tu votación ya se encuentra registrada.</p>
		<input type="submit" name="" value="Volver">
        </form>
    </div>
    </div>
        
    
     <div class="imgBx">
        <img src="https://images.unsplash.com/photo-1481824429379-07aa5e5b0739?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60">
    </div>
    </div>
    
</section>
  </body>
</html>';


}

?>