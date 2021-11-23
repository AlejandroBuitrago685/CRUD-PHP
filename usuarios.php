<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="assets/styles/users.css" media="screen" />
        <link rel="shortcut icon" href="assets/imgs/fav.png"/>
        <title>Gestor Comercial</title>
    </head>
    <body>


        <div class="wrapper">
            <ul class="menu">
                <li><a href="acceso.php">Inicio</a></li>
                <li><a href='formUsuarios.php?type=add'>Crear nuevo usuario</a></li>
            </ul>
        </div>

        <?php
        
            if(!isset($_SESSION)) session_start(); //Comporbamos la sesión
            if(isset($_SESSION["rol"]) && $_SESSION["rol"] != "admin") header("location: userRedirection.php"); //Añado también el rol para mayor seguridad

            $columns = array('UserID','FullName','Email', 'LastAccess', 'Enabled', 'Manejo');
            $column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];
            $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

            $asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';
        ?>

        <?php
                require "Conexion/BaseDatos.php";
                $page = 0;
                $min = 0;
                $size= 10;
                $nUsers = getUsersCount();
                
                if(!isset($_GET['page'])){
                    header("location: usuarios.php?page=1");
                }
                else {
                    $page = intval($_GET['page']) -1;
                    $min = $size * $page;
                    $page = $page + 1;
                }

                $listado = getUsersOrderBy($column, $sort_order, $min, $size); //Obtenemos los productos ya ordenados, ya que pasmos los datos necesarios al método de la consulta
        ?>

        <table style="border=1px solid black">


            <tr>
                <th><a href="usuarios.php?page=1&column=UserID&order=<?php echo $asc_or_desc; ?>">ID</a></th>
                <th><a href="usuarios.php?page=1&column=FullName&order=<?php echo $asc_or_desc; ?>">Nombre</a></th>
                <th><a href="usuarios.php?page=1&column=Email&order=<?php echo $asc_or_desc; ?>">Email</a></th>
                <th><a href="usuarios.php?page=1&column=LastAccess&order=<?php echo $asc_or_desc; ?>">Último acceso</a></th>
                <th><a href="usuarios.php?page=1&column=Enabled&order=<?php echo $asc_or_desc; ?>">Enabled</a></th>
                <th>Manejo</th>
            </tr>
            
            <?php
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
                                if($isAdmin){
                                    echo '<button class="editar" disabled></button>' . '<button class="borrar" disabled></button>'; //Si el usuario de la fila es SuperAdmin deshabilitamos los botones de modificar y borrar
                                }
                                else{
                                    echo '<button class="editar" onclick="window.location.href='."'formUsuarios.php?type=modify&id=".$row['UserID']."'\"".'></button>';
                                    echo '<button class="borrar" onclick="window.location.href='."'formUsuarios.php?type=delete&id=".$row['UserID']."'\"".'></button>';
                                }
                            ?>
                        </td>
                        </tr>
                    <?php endwhile;  } //Cerramos el if de rol de usuario y terminamos el bucle?>
        </table>
        <div class="pagination">
        <?php

            if($page > 1){
                echo "<button style=\"margin-right: 10px;\" onclick=\"window.location.href='usuarios.php?page=".$page-1 ."'\">Anterior</button>";
            }

            echo "<label>Página ".$page." / ".ceil($nUsers/$size)."</label>";

            if(ceil($nUsers/$size) > $page ){
                echo "<button style=\"margin-left:10px;\" onclick=\"window.location.href='usuarios.php?page=".$page+1 ."'\">Siguiente</button>";
            }

            ?>
        </div>
    </body>
</html>