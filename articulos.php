<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="assets/styles/article.css" media="screen" />
        <link rel="shortcut icon" href="assets/imgs/fav.png"/>
        <title>Gestor Comercial</title>
    </head>
    <body>

    <div class="wrapper">
            <ul class="menu">
                <li><a href="acceso.php">Inicio</a></li>
                
                    <?php 
                    if(!isset($_SESSION)) session_start(); //Comporbamos si la sesión está inciada
                    if(isset($_SESSION["rol"]) && ($_SESSION["rol"] === "admin" || $_SESSION["rol"] === "autorizado")){ //Si el usuario es superAdmin o autorizado se muestra el botón.
                        
                        echo '<li><a style="margin-left:10px;" href='."'formArticulos.php?type=add'".'>Crear nuevo artículo</a></li>';
                    }
        
                    ?>
                
            </ul>
        </div>

        <?php
            if(!isset($_SESSION)) session_start(); //Comporbamos si la sesión está inciada

            if(isset($_SESSION["rol"]) && !$_SESSION["rol"] === "admin") header("location: userRedirection.php");
            
            $columns = array('ProductID','CategoryID','Name', 'Cost', 'Price'); //Array con las columnas de la tabla
            $column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0]; //Si existe column y se encuentra en el array $columns coge column sino existe coge el primer elemento del array $columns
            $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC'; //Indica a través de los headers de qué manera se ordena la tabla (ASC o DESC)

            $asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc'; //Esto varía el orden de ordenación de la tabla en ascendente o descendente
        ?>

        <?php
                require "Conexion/BaseDatos.php";
                $page = 0;
                $min = 0;
                $size= 10;
                $nProducts = getProductsCount();
                
                if(!isset($_GET['page'])){
                    header("location: articulos.php?page=1");
                }
                else {
                    $page = intval($_GET['page']) -1;
                    $min = $size * $page;
                    $page = $page + 1;
                }


                $listado = getProductsOrderBy($column, $sort_order, $min, $size); //Obtenemos los productos ya ordenados, ya que pasmos los datos necesarios al método de la consulta
        ?>

        <table style="border=1px solid black">
            <tr>
                <th><a href="articulos.php?page=1&column=ProductID&order=<?php echo $asc_or_desc; ?>">ID</a></th> <!--Creamos cada encabezado e insertamos el header correspondiente para su ordenación-->
                <th><a href="articulos.php?page=1&column=CategoryID&order=<?php echo $asc_or_desc; ?>">Categoría</a></th>
                <th><a href="articulos.php?page=1&column=Name&order=<?php echo $asc_or_desc; ?>">Nombre</a></th>
                <th><a href="articulos.php?page=1&column=Cost&order=<?php echo $asc_or_desc; ?>">Coste</a></th>
                <th><a href="articulos.php?page=1&column=Price&order=<?php echo $asc_or_desc; ?>">Precio</a></th>
                <?php if(isset($_SESSION["rol"]) && $_SESSION["rol"] === "admin") echo "<th>Manejo</th>"; ?> <!--Si es superAdmin mostramos esta columna/encabezado-->  
            </tr>

            <?php 
               foreach($listado as $fila){ //Mostramos una fila por cada producto que haya en la BBDD
                    $category = getCategory($fila); //Esta consulta nos devuelve el nombre de la categoría
                    foreach($category as $cat){ //Realizamos esta iteración para poder mostrar los nombres de las categorías en lugar de su ID
                        echo "<tr><td>" . $fila['ProductID'] . "</td>";
                        echo "<td>" . $cat['Name'] . "</td>";
                        echo "<td>" . $fila['Name'] . "</td>";
                        echo "<td>" . $fila['Cost'] . "</td>";
                        echo "<td>" . $fila['Price'] . "</td>";
                        if(isset($_SESSION["rol"]) && $_SESSION["rol"] === "admin"){ //Comprobamos si el usuario es SuperAdmin para mostrar o no los botones de modificación y borrado
                            echo "<td>" . '<button class="editar-button" onclick="window.location.href='."'formArticulos.php?type=modify&id=".$fila['ProductID']."'\"".'></button>';
                            echo '<button class="borrar-button" onclick="window.location.href='."'formArticulos.php?type=delete&id=".$fila['ProductID']."'\"".'></button>' . "</td></tr>";
                        }   
                    }
                }
            ?>
            
        </table>
        <div class="pagination">
            <?php

            if($page > 1){
                echo "<button style=\"margin-right: 10px;\" onclick=\"window.location.href='articulos.php?page=".$page-1 ."'\">Anterior</button>";
            }

            echo "<label  >Página ".$page." / ".ceil($nProducts/$size)."</label>";

            if(ceil($nProducts/$size) > $page ){
                echo "<button style=\"margin-left:10px;\" onclick=\"window.location.href='articulos.php?page=".$page+1 ."'\">Siguiente</button>";
            }

            ?>

        </div>
    </body>
</html>