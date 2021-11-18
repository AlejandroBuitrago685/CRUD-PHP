<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="assets/styles/articles.css" media="screen" />
        <link rel="shortcut icon" href="assets/imgs/fav.png"/>
        <title>Gestor Comercial</title>
    </head>
    <body>

        <?php
            if(!isset($_SESSION)) session_start(); //Comporbamos la sesión

            if(isset($_SESSION["rol"]) && !$_SESSION["rol"] === "admin") header("location: userRedirection.php");
            
            $columns = array('ProductID','CategoryID','Name', 'Cost', 'Price'); //Array con las columnas de la tabla
            $column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];
            $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC'; //Indica a través de los headers de qué manera se ordena la tabla (ASC o DESC)

            $asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc'; //Esto varía el orden de ordenación de la tabla en ascendente o descendente
        ?>
        <?php 
            if(isset($_SESSION["rol"]) && ($_SESSION["rol"] === "admin" || $_SESSION["rol"] === "autorizado")){ //Si el usuario es superAdmin o autorizado se muestra el botón.
                
                echo '<button style="margin-left:10px;" onclick="location.href='."'formArticulos.php?type=add'".'">Crear nuevo artículo</button>';
            }
        
        ?>

        <table style="border=1px solid black">
            <tr>
                <th><a href="articulos.php?column=ProductID&order=<?php echo $asc_or_desc; ?>">ID</a></th> <!--Creamos cada encabezado e insertamos el header correspondiente para su ordenación-->
                <th><a href="articulos.php?column=CategoryID&order=<?php echo $asc_or_desc; ?>">Categoría</a></th>
                <th><a href="articulos.php?column=Name&order=<?php echo $asc_or_desc; ?>">Nombre</a></th>
                <th><a href="articulos.php?column=Cost&order=<?php echo $asc_or_desc; ?>">Coste</a></th>
                <th><a href="articulos.php?column=Price&order=<?php echo $asc_or_desc; ?>">Precio</a></th>
                <?php if(isset($_SESSION["rol"]) && $_SESSION["rol"] === "admin") echo "<th>Manejo</th>"; ?> <!--Si es superAdmin mostramos esta columna/encabezado-->  
            </tr>

            <?php
                require "Conexion/BaseDatos.php";
                $listado = getProductsOrderBy($column, $sort_order); //Obtenemos los productos ya ordenados, ya que pasmos los datos necesarios al método de la consulta
            ?>

            <?php 
               foreach($listado as $tabla){//Mostramos una fila por cada producto que haya en la BBDD
                    $category = getCategory($tabla); //Esta consulta nos devuelve el nombre de la categoría
                    foreach($category as $cat){ //Realizamos esta iteración para poder mostrar los nombres de las categorías ne lugar de su ID
                        echo "<tr><td>" . $tabla['ProductID'] . "</td>";
                        echo "<td>" . $cat['Name'] . "</td>";
                        echo "<td>" . $tabla['Name'] . "</td>";
                        echo "<td>" . $tabla['Cost'] . "</td>";
                        echo "<td>" . $tabla['Price'] . "</td>";
                        if(isset($_SESSION["rol"]) && $_SESSION["rol"] === "admin"){ //Comprobamos si el usuario es SuperAdmin para mostrar o no los botones de modificación y borrado
                            echo "<td>" . '<button class="editar-button" onclick="window.location.href='."'formArticulos.php?type=modify&id=".$tabla['ProductID']."'\"".'></button>';
                            echo '<button class="borrar-button" onclick="window.location.href='."'formArticulos.php?type=delete&id=".$tabla['ProductID']."'\"".'></button>' . "</td></tr>";
                        }   
                    }
                    
                }
                ?>

        </table>

    </body>
</html>