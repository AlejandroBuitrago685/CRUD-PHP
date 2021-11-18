<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="assets/styles/access.css" media="screen" />
	<link rel="shortcut icon" href="assets/imgs/fav.png"/>
	<title>Gestor Comercial</title>
</head>

    <body>
        <div class="container">
                    <?php
                        if(!isset($_SESSION)) session_start(); //Comprobamos si la sesión se ha iniciado, sino la iniciamos
                        if(isset($_SESSION["userName"]) && isset($_SESSION["rol"])){ //Añado también el rol para mayor seguridad
                            echo '<div style="margin-bottom:10px">';
                            echo "<label>Bienvenido de nuevo $_SESSION[userName]</label>"; //Esta línea muestra un mensaje peresonalizado con el nombre de usuario
                            echo "</div><hr>";
                            echo '<button onclick="location.href='."'articulos.php'".'">Artículos</button>'; //Botón para acceder a artículos

                            if(isset($_SESSION["rol"]) && $_SESSION["rol"] === 'admin'){ //Comprobamos si el usuario es superadmin y mostramos el botón dependiendo de su rol
                                echo '<button style="margin-left:10px;" onclick="location.href='."'usuarios.php'".'">Usuarios</button><br><br>';
                            }
                            echo '<button style="margin-left:10px;" onclick="location.href='."'index.php?logout=true'".'">Volver</button>';
                        }
                        else header("location: userRedirection.php"); //Si el usuario no está logueado lo redireccionamos a una página indicándoselo para darle feedback
                    ?>
        </div>                
    </body>
</html>