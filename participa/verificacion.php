<?php

$control_telefono=false; //Variable de verificación de que el teléfono se encuentra en el censo
$control_dni=false; //Variable de verificación de que el DNI se encuentra en el censo
$control_voto=false; //Variable de verificación de posible votación previa con el mismo DNI

//Comprobación de que el teléfono está en el censo. Se incluye el teléfono en el censo para evitar votaciones fraudulentas de quien conozca el DNI de esta persona. De esta forma, solo se puede votar si el teléfono al que se envía el código de verificación se encuentra en el censo.
$lista_telefonos = fopen("telefonos.txt", "r") or exit("Error abriendo fichero listado telefonos!");
while ($linea = fgets($lista_telefonos)) {
	
    if ($_REQUEST[telefono] == substr($linea, 0, 9)) {
    	$control_telefono = true;  
	}
    $numlinea++;
}
fclose($lista_telefonos);

//Comprobación de que el DNI está en el censo
$lista_dni = fopen("dni.txt", "r") or exit("Error abriendo fichero listado dni!");
while ($linea = fgets($lista_dni)) {
	
    if ($_REQUEST[dni] == substr($linea, 0, 8)) {
    	$control_dni = true;  
	}
    $numlinea++;
}
fclose($lista_dni);

// Verificación de votos anteriores
$dni_votos = fopen("dni_votos.txt", "r") or exit("Error abriendo fichero listado dni votos!");
while ($linea = fgets($dni_votos)) {

    if (md5($_REQUEST[dni]) == substr($linea, 0, 32)) {
    	$control_voto = true; 
		
	}
    $numlinea++;
}
fclose($dni_votos);

//Generación del código de verificación que se envía al teléfono
$verificacion= (string) rand(10000, 99999);

// Si el teléfono, el DNI, figuran en el censo, no ha votado previamente y viene de la página inicial, continúa el proceso

if ($control_telefono==true && $control_dni==true && $control_voto==false && $_REQUEST[control]==1 ){
		//Envio del mensaje al móvil con la pasarela de SMS elegida.	
	
		$tel_envio = "34".$_REQUEST[telefono];
		
		$post["to"] = array($tel_envio); 
		$post["message"] = "Codigo de verificacion: ".$verificacion; 
		$post["from"] = "UGT";
		$user = "************";
		$password = "********";
		try {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://dashboard.360nrs.com/api/rest/sms"); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post)); curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Accept: application/json",
		"Authorization: Basic " . base64_encode($user . ":" . $password))); $result = curl_exec($ch);
		//var_dump($result);
		} catch (Exception $exc) {
		echo $exc->getTraceAsString();
		}
		echo $verificacion;
		
	//Muestra el formulario para introducir el código recibido en el móvil
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
      <form action="votacion.php" method="post">
          <h2>Acceder a la votación</h2>
        <input type="text" name="control" placeholder="Código de verificación">
		<input type="hidden" name="verificacion" value='.$verificacion.' />
		<input type="hidden" name="dni" value='.$_REQUEST[dni].' />
		
		
        <input type="submit" name="" value="Continuar">
        <p class="signup">El sistema verificará el código que hemos enviado a tu móvil.</p>
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

//En caso de que se detecte que ese DNI ya había votado, notifica esta situación e impide continuar
else if ($control_voto==true){
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
       
        <p class="signup">Tu votación ya se encuentra registrada.</p>
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

//En caso de no figurar en el censo, notifica esta circunstancia e impide continuar.
else{
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
          <h2>Los datos facilitados no figuran en el censo</h2>
       
        <p class="signup">Los datos que nos has facilitado no figuran en el censo, revisa el DNI (sin letra) y el teléfono movil y en caso de ser correctos ponte en contacto con la Secretaría de Organización.</p>
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