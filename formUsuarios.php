<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="assets/styles/index.css" media="screen" />
	<link rel="shortcut icon" href="assets/imgs/fav.png"/>
	<title>Gestor Comercial</title>
</head>

    <body>
        
        <?php
            if(!isset($_SESSION)) session_start();
            
            if(!isset($_SESSION["userName"]) && !isset($_SESSION["rol"])) header("location: userRedirection.php"); //Añado también el rol para mayor seguridad

            require "Conexion/BaseDatos.php";
            $type = "";
            $user = "";

            if(isset($_GET["type"])) $type = $_GET["type"];

            if(isset($_GET["id"])){
                $id = $_GET["id"];
                $user = getUsersByID($id) -> fetch_assoc();
            } 

            if(isset($_GET["delete"]) && $_GET["delete"] === 'success'){
                
                if(deleteUser(intval($id))){
                    echo '<div style="text-align: center;">';
                        echo '<label>Se ha eliminado el usuario correctamente.</label><br>';
                        echo "<button class=\"button\" onclick=\"window.location.href='usuarios.php'\">Volver a los usuarios</button>";
                    echo '</div>';
                    return;
                }

                else {
                    echo '<div style="text-align: center;">';
                        echo '<label>Ha ocurrido un error durante la eliminación del usuario.</label><br>';
                        echo "<button class=\"button\" onclick=\"window.location.href='usuarios.php'\">Volver a los usuarios</button>";
                    echo '</div>';
                    return;
                }
            } 

            if(isset($_GET["modify"]) && $_GET["modify"] === 'success' && isset($_POST['idUser']) && isset($_POST['nombre']) && isset($_POST['access']) && isset($_POST['pass']) && isset($_POST['email']) && isset($_POST['enabled'])){
                $UserID = $_POST['idUser'];
                $FullName = $_POST['nombre'];
                $LastAccess = $_POST['access'];
                $Password = $_POST['pass'];
                $Email = $_POST['email'];
                $Enabled = $_POST['enabled'];
                $user = array("UserID" => intval($UserID), "FullName" => $FullName, "LastAccess" => date_format(date_create($LastAccess),'Y/m/d'), "Password" => $Password, "Email" => $Email, "Enabled" => $Enabled);
                
                if(modifyUser($user)){
                    echo '<div style="text-align: center;">';
                        echo '<label>Se ha modificado el usuario correctamente.</label><br><br>';
                        echo "<button class=\"button\" onclick=\"window.location.href='usuarios.php'\">Volver a los usuarios</button>";
                    echo '</div>';
                    return;
                }

                else {
                    echo '<div style="text-align: center;">';
                        echo '<label>Ha ocurrido un error al modificar el usuario.</label><br><br>';
                        echo "<button class=\"button\" onclick=\"window.location.href='usuarios.php'\">Volver a los usuarios</button>";
                    echo '</div>';
                    return;
                }
            }

            if(isset($_GET["add"]) && $_GET["add"] === 'success' && isset($_POST['idUser']) && isset($_POST['nombre']) && isset($_POST['access']) && isset($_POST['pass']) && isset($_POST['email']) && isset($_POST['enabled'])){
                $UserID = $_POST['idUser'];
                $FullName = $_POST['nombre'];
                $LastAccess = $_POST['access'];
                $Password = $_POST['pass'];
                $Email = $_POST['email'];
                $Enabled = $_POST['enabled'];
                $user = array("UserID" => intval($UserID), "FullName" => $FullName, "LastAccess" => date_format(date_create($LastAccess),'Y/m/d'), "Password" => $Password, "Email" => $Email, "Enabled" => $Enabled);
                if(addUser($user)){
                    echo '<div style="text-align: center;">';
                        echo '<label>Se ha agregado el usuario correctamente.</label><br><br>';
                        echo "<button class=\"button\" onclick=\"window.location.href='usuarios.php'\">Volver a los usuarios</button>";
                    echo '</div>';
                    return;
                }

                else {
                    echo '<div style="text-align: center;">';
                        echo '<label>Ha ocurrido un error al agregar el usuario.</label><br><br>';
                        echo "<button class=\"button\" onclick=\"window.location.href='usuarios.php'\">Volver a los usuarios</button>";
                    echo '</div>';
                    return;
                }
                
            }


        ?>

        

        <div class="formulary">

                            <header class="formulary__header">
                                <h2>
                                    <?php 
                                    if($type == "modify"){
                                        echo  '<label>Se va a modificar un usuario</label>';
                                    }
                                    if($type == "delete"){
                                        echo  '<label>Se va a eliminar un usuario</label>';
                                    }
                                    if($type == "add"){
                                        echo  '<label>Se va a añadir un usuario nuevo</label>';
                                    }
                                    ?>
                                </h2>
                            </header>
                            
                    <form action="
                    <?php
                    
                        if($type == "modify"){
                            echo "formUsuarios.php?modify=success";
                        }
                        if($type == "add"){
                            echo "formUsuarios.php?add=success";
                        }

                    ?>" class="formulary__form" method="POST" id="formulario">

                        <div>
                            <label for="idUser">ID</label>
                            <?php 
                                if($type == "modify" || $type == "delete"){
                                    echo '<input type="number" id="idUser" readonly name="idUser" placeholder="ID"' . "value='$user[UserID]'>";
                                }
                                else {
                                    echo '<input type="number" id="idUser" readonly name="idUser"  placeholder="ID">';
                                }
                            ?> 
                            
                        </div>
            
                        <div>
                            <label for="nombre">Nombre</label>
                            <?php 
                                if($type == "modify" || $type == "delete"){
                                    echo '<input type="text" id="nombre" required name="nombre" placeholder="Jhon Doe"'. "value='$user[FullName]'>";
                                }
                                else {
                                    echo '<input type="text" id="nombre" required name="nombre" placeholder="Jhon Doe">';
                                }
                            ?> 
                            
                        </div>

                        <div>
                            <label for="pass">Contraseña</label>
                            <?php 
                                if($type == "modify" || $type == "delete"){
                                    echo '<input type="password" id="pass" required name="pass"' . "value='$user[Password]'>";
                                }
                                else {
                                    echo '<input type="password" id="pass" required name="pass">';
                                }
                            ?>
                        </div>

                        <div>
                            <label for="email">Correo</label>
                            <?php 
                                if($type == "modify" || $type == "delete"){
                                    echo '<input type="email" id="email" required name="email" placeholder="example@gmail.com"' . "value='$user[Email]'>";
                                }
                                else {
                                    echo '<input type="email" id="email" required name="email" placeholder="example@gmail.com">';
                                }
                            ?>
                           
                        </div>

                        <div>
                            <label for="access">Último acceso</label>
                            <?php 
                                if($type == "modify" || $type == "delete"){
                                    echo '<input type="date" id="access" required name="access"' . "value='$user[LastAccess]'>";
                                }
                                else {
                                    echo '<input type="date" id="access" required name="access">';
                                }
                            ?>
                           
                        </div>
                        <div>
                            <label for="enabled">Autorizado</label>
                            <?php
                                if($type == "modify" || $type == "delete"){
                                    echo '<fieldset id="enabled">';
                                    if($user['Enabled'] == 1){
                                        echo '<input type="radio" value="1" name="enabled" checked> Sí<br>';
                                    }
                                    else {
                                        echo '<input type="radio" value="1" name="enabled"> Sí<br>';
                                    }

                                    if($user['Enabled'] == 0){
                                        echo '<input type="radio" value="0" name="enabled" checked> No<br>';
                                    }
                                    else {
                                        echo '<input type="radio" value="0" name="enabled"> No<br>';
                                    }
                                    echo '</fieldset>';
                                }
                                else {
                                    echo '<fieldset id="enabled">';
                                        echo '<input type="radio" value="1" name="enabled"> Sí<br>';
                                        echo '<input type="radio" value="0" name="enabled"> No<br>';
                                    echo '</fieldset>';
                                }
                            ?>
                        </div>

                    </form>

                    <div>
                        <button class="button" onclick="window.location.href='usuarios.php'">Volver</button>
                            <?php 
                                if($type == "add"){
                                    echo '<button class="button" type="submit" form="formulario">Añadir</button>';
                                }

                                if($type == "modify" ){
                                    echo '<button class="button" type="submit" form="formulario"';
                                    echo '>Modificar</button>';
                                }

                                if($type == "delete"){
                                    echo '<button class="button" type="submit"';
                                    echo "onclick=\"window.location.href='formUsuarios.php?delete=success&id=".$id."'\"";
                                    echo '>Borrar</button>';
                                }
                            
                            ?>
                    </div>
                    
        </div>                
    </body>
</html>