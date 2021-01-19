<?php

// Verifica que el código enviado al móvil coincide con el insertado en el campo de verificación de la página anterior, si coincide continua la ejecución.
if ($_REQUEST[verificacion]==$_REQUEST[control]){
	//Muestra la página de votación
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
		  <form action="fin.php" method="post">
			  <h2>Votación</h2>
			<input type="hidden" name="dni" value='.$_REQUEST[dni].' /><p>
			<input type="hidden" name="control" value=1 /><p>';
	
	// Carga la pregunta del fichero pregunta.txt y la muestra en la pantalla
	$f_pregunta = fopen("pregunta.txt", "r") or exit("Error abriendo fichero listado telefonos!");
	$pregunta = fgets($f_pregunta); 
	fclose($f_pregunta);
	echo $pregunta;
	
	// Carga del fichero respuestas.txt las posibles respuestas y las muestra
	echo '<table class="default">';
	echo '<tr><td>&nbsp;</td></tr>';
	$f_respuestas = fopen("respuestas.txt", "r") or exit("Error abriendo fichero listado telefonos!");
	$pos=1;
	
	while ($linea = fgets($f_respuestas)) {
		echo '<tr>
    			<td><input type="radio" name="voto" value="'.$pos.'"></td>
				<td>&nbsp;</td>
    			<td>'.$linea.'</td></tr><tr><td>&nbsp;</td></tr>';	
		
		
		$pos++;
	}		
  	fclose($f_respuestas);
	
	
	echo '</table>

			<input type="submit" name="" value="Votar">
			
			<p class="signup">El sistema registrará la votación de forma anónima.</p>
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

// En caso de que la verificación del código enviado al movil no sea correcta, muestra el mensaje de error
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
		  <form action="index.php" method="post">
			  <h2>Datos no verificados</h2>
			  
			<p class="signup">Puedes volver a iniciar el proceso de verificación o contactar con la Secretaría de Organización para verificar tus datos.</p>
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