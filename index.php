<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="assets/styles/index.css" media="screen" />
	<link rel="shortcut icon" href="assets/imgs/fav.png"/>
	<title>Gestor Comercial</title>
</head>

<body class="align">

	<?php 	
		include "Conexion/BaseDatos.php";

		Conectar();
		
			if(isset($_GET["logout"]) && $_GET["logout"] === 'true'){ //Si por el header recibimos logout=true cerramos la sesión
                logout();
        	}

	?>

	<div class="login">

		<header class="login__header">
			<h2><svg class="icon">
				<use xlink:href="#icon-lock"/>
			</svg>Iniciar sesión</h2>
		</header>

		<form action="Conexion/login.php" class="login__form" method="POST"> <!--Creamos el formulario y le pasamos la información a login.php-->

			<div>
				<label for="nombre">Nombre completo*</label>
				<input type="text" id="nombre" name="nombre" placeholder="Jhon Doe">
			</div>

			<div>
				<label for="email">Correo electrónico*</label>
				<input type="email" id="email" name="email" placeholder="ejemplo@gmail.com">
			</div>

			<?php

				if(!isset($_SESSION)) session_start(); //Si no estaba creada la sesión la creamos.

				if(isset($_GET["credentials"]) && $_GET["credentials"] === 'true') echo "<label style='color:red'>Usuario o contraseña inválido.</label>"; //Muestra el mensaje de error (Muy posiblemente se pueda hacer de una manera más óptima)

				if(isset($_GET["nofields"]) && $_GET["nofields"] === 'true') echo "<label style='color:red'>Por favor, rellene todos los campos.</label>"; //Muestra el mensaje de error (Muy posiblemente se pueda hacer de una manera más óptima)

				if(isset($_GET["logued"]) && $_GET["logued"] === 'true') echo "<label style='color:black; text-align:center;'>Bienvenido"." $_SESSION[userName], "."pulsa"."
				<a href='acceso.php'>AQUÍ</a>"." para continuar."."</label>"; //Si se ha logueado correctamente muestra el mensaje de redirección

     		?>

			<div>
				<input class="button" type="submit" value="Iniciar sesión">
			</div>

		</form>
	</div>

<svg xmlns="http://www.w3.org/2000/svg" class="icons">

  <symbol id="icon-lock" viewBox="0 0 448 512">
	<path d="M400 224h-24v-72C376 68.2 307.8 0 224 0S72 68.2 72 152v72H48c-26.5 0-48 21.5-48 48v192c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V272c0-26.5-21.5-48-48-48zm-104 0H152v-72c0-39.7 32.3-72 72-72s72 32.3 72 72v72z" />
  </symbol>

</svg>

</body>
</html>