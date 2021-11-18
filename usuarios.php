<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="assets/styles/users.css" media="screen" />
        <link rel="shortcut icon" href="assets/imgs/fav.png"/>
        <title>Gestor Comercial</title>
    </head>
    <body>

        <?php
        
            if(!isset($_SESSION)) session_start(); //Comporbamos la sesión
            if(isset($_SESSION["rol"]) && $_SESSION["rol"] != "admin") header("location: userRedirection.php"); //Añado también el rol para mayor seguridad

            $columns = array('UserID','FullName','Email', 'LastAccess', 'Enabled', 'Manejo');
            $column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];
            $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

            $asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';
        ?>

        <button class="create-button">Crear nuevo usuario</button>

        <table style="border=1px solid black">


            <tr>
                <th><a href="usuarios.php?column=UserID&order=<?php echo $asc_or_desc; ?>">ID</a></th>
                <th><a href="usuarios.php?column=FullName&order=<?php echo $asc_or_desc; ?>">Nombre</a></th>
                <th><a href="usuarios.php?column=Email&order=<?php echo $asc_or_desc; ?>">Email</a></th>
                <th><a href="usuarios.php?column=LastAccess&order=<?php echo $asc_or_desc; ?>">Último acceso</a></th>
                <th><a href="usuarios.php?column=Enabled&order=<?php echo $asc_or_desc; ?>">Enabled</a></th>
                <th>Manejo</th>
            </tr>
            
            <?php

                require "Conexion/BaseDatos.php";
                $listado = getUsersOrderBy($column, $sort_order);

                if(isset($_SESSION["rol"]) && $_SESSION["rol"] === "admin"){ //A modo de seguridad comprobamos el rol de usuario
            ?>

                    <?php while ($row = $listado->fetch_assoc()): 
                        $isAdmin = isSuperAdmin($row); //Por cada iteración comprobamos si el usuario de esa fila es admin o no

                        ?>
                        <tr
                            <?php 
                                if($isAdmin) echo 'style="color:red"'; //Si el usuario de la tabla es SuperAdmin lo muestra resaltado en rojo
                            ?>
                        >
                        <td><?php echo $row['UserID']; ?></td>
                        <td><?php echo $row['FullName']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo date_format(date_create($row['LastAccess']),'d/m/y'); ?></td> <!--Formateamos la fecha acorde de lo indicado en el ejercicio-->
                        <td><?php echo $row['Enabled']; ?></td>
                        <td>
                            <?php 
                                if($isAdmin) echo '<button class="editar" disabled></button>' . '<button class="borrar" disabled></button>'; //Si el usuario de la fila es SuperAdmin deshabilitamos los botones de modificar y borrar

                                else echo '<button class="editar"></button>' . '<button class="borrar"></button>';
                            ?>
                        </td>
                        </tr>
                    <?php endwhile;  } //Cerramos el if de rol de usuario y terminamos el bucle?>
        </table>

    </body>
</html>