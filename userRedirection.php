<!DOCTYPE html><!--Este archivo únicamente hace la función de restringir el paso al usuario a modo de seguridad en caso de que no esté logueado y dar feedback al mismo-->
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="assets/styles/access.css" media="screen" />
	<link rel="shortcut icon" href="assets/imgs/fav.png"/>
	<title>Gestor Comercial</title>
</head>

    <body>
        <div class="container">
            <div style="margin-bottom:10px">
                <label>Acceso restringido</label>
            </div><hr>
                <label>Sólo usuarios registrados/logueados pueden<br>acceder a este contenido.</label><br><br>
                <button style="margin-left:10px;" onclick="location.href='index.php?logout=true'">Volver</button>
        </div>                
    </body>
</html>