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
            $article = "";

            if(isset($_GET["type"])) $type = $_GET["type"];//Esta varibale nos indicará que acción estamos realizando (agregar, modificar o borrar)

            if(isset($_GET["id"])){ //Así obtenemos los productos para setearlos en el formulario cuando modificamos o borramos
                $id = $_GET["id"];
                $article = getProductsByID($id) -> fetch_assoc();
            } 

            if(isset($_GET["delete"]) && $_GET["delete"] === 'success'){
                
                if(deleteProduct(intval($id))){//Comprobamos el header para saber si estamos en modo de borrado y pasamos el id a la consulta
                    echo '<div style="text-align: center;">';
                        echo '<label>Se ha eliminado el producto correctamente.</label><br>';
                        echo "<button class=\"button\" onclick=\"window.location.href='articulos.php'\">Volver a los artículos</button>";
                    echo '</div>';
                    return;
                }

                else {
                    echo '<div style="text-align: center;">';
                        echo '<label>Ha ocurrido un error al eliminar el producto.</label><br>';
                        echo "<button class=\"button\" onclick=\"window.location.href='articulos.php'\">Volver a los artículos</button>";
                    echo '</div>';
                    return;
                }
                
            } 
            if(isset($_GET["modify"]) && $_GET["modify"] === 'success' && isset($_POST['idproducto']) && isset($_POST['nombre']) && isset($_POST['precio']) && isset($_POST['coste']) && isset($_POST['categoria'])){//Comprobamos la cabecera para saber si estamos modificando
                $ProductID = $_POST['idproducto'];//Obtenemos los parámetros del formulario mediante su name
                $Name = $_POST['nombre'];//Asignamos cada valor del formulario a cada valor del producto
                $Price = $_POST['precio'];
                $Cost = $_POST['coste'];
                $CategoryID = $_POST['categoria'];//Creamos un diccionario para pasarlo a la función y manejar los datos más cómodamente
                $product = array("ProductID" => intval($ProductID), "Name" => $Name, "Price" => floatval($Price), "Cost" => floatval($Cost), "CategoryID" => intval($CategoryID));
                
                if(modifyProduct($product)){//Si el producto se ha añadido satisfactoriamente mostramos este if, sino mostramos el else
                    echo '<div style="text-align: center;">';
                        echo '<label>Se ha modificado el producto correctamente.</label><br><br>';
                        echo "<button class=\"button\" onclick=\"window.location.href='articulos.php'\">Volver a los artículos</button>";
                    echo '</div>';
                    return;
                }

                else {
                    echo '<div style="text-align: center;">';
                        echo '<label>Ha ocurrido un error al modificar el producto.</label><br><br>';
                        echo "<button class=\"button\" onclick=\"window.location.href='articulos.php'\">Volver a los artículos</button>";
                    echo '</div>';
                    return;
                }
                
            }
            
            if(isset($_GET["add"]) && $_GET["add"] === 'success' && isset($_POST['nombre']) && isset($_POST['precio']) && isset($_POST['coste']) && isset($_POST['categoria'])){
                $Name = $_POST['nombre']; //Obtenemos los parámetros del formulario mediante su name
                $Price = $_POST['precio'];//Asignamos cada valor del formulario a cada valor del producto
                $Cost = $_POST['coste'];
                $CategoryID = $_POST['categoria'];
                $product = array("Name" => $Name, "Price" => floatval($Price), "Cost" => floatval($Cost), "CategoryID" => intval($CategoryID)); //Creamos un diccionario para pasarlo a la función
                if(addProduct($product)){ //Si el producto se ha añadido satisfactoriamente mostramos este if, sino mostramos el else
                    echo '<div style="text-align: center;">';
                        echo '<label>Se ha agregado el producto correctamente.</label><br><br>';
                        echo "<button class=\"button\" onclick=\"window.location.href='articulos.php'\">Volver a los artículos</button>";
                    echo '</div>';
                    return;
                }

                else {
                    echo '<div style="text-align: center;">';
                        echo '<label>Ha ocurrido un error al agregar el producto.</label><br><br>';
                        echo "<button class=\"button\" onclick=\"window.location.href='articulos.php'\">Volver a los artículos</button>";
                    echo '</div>';
                    return;
                }
                
            }

        ?>

        <div class="formulary">
                            <header class="formulary__header">
                                <h2>
                                    <?php //Dependiendo de la acción que realicemos, mostraremos un texto u otro
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
                    
                        if($type == "modify"){ //Dependiendo de la acción que realicemos, mandaremos diferentes cabeceras
                            echo "formArticulos.php?modify=success";
                        }
                        if($type == "add"){
                            echo "formArticulos.php?add=success";
                        }

                    ?>" class="formulary__form" method="POST" id="formulario">

                        <div>
                            <label for="idproducto">ID</label>
                            <?php 
                                if($type == "modify" || $type == "delete"){//Dependiendo de la acción que realicemos, rellenaremos los campos o no, así con todos los campos del formulario
                                    echo '<input type="number" id="idproducto" readonly name="idproducto" placeholder="ID"' . "value='$article[ProductID]'>";
                                }
                                else {
                                    echo '<input type="number" id="idproducto" readonly name="idproducto"  placeholder="ID">';//Lo ponemos como readonly ya que desde mi punto de vista debe ser un campo no modificable ya que es autoincremental
                                }
                            ?> 
                            
                        </div>

                        <div>
                            <label for="select">Categoría</label>

                            <?php 
                                if($type == "modify" || $type == "delete"){
                                    echo '<select id="categoria" name="categoria">';
                                    listaCategoriasSelected($article['CategoryID']);//Esto selecciona la categoría del producto a modificar o borrar por defecto
                                    echo '</select>';
                                }
                                else {
                                    echo '<select name="categoria">';
                                    listaCategorias();
                                    echo '</select>';
                                }
                            ?> 
                            
                        </div>
            
                        <div>
                            <label for="nombre">Nombre</label>
                            <?php 
                                if($type == "modify" || $type == "delete"){
                                    echo '<input type="text" id="nombre" required name="nombre" placeholder="Pantalón Talla M"'. "value='$article[Name]'>";
                                }
                                else {
                                    echo '<input type="text" id="nombre" required name="nombre" placeholder="Pantalón Talla M">';
                                }
                            ?> 
                            
                        </div>

                        <div>
                            <label for="coste">Coste</label>
                            <?php 
                                if($type == "modify" || $type == "delete"){
                                    echo '<input type="number" step="any" id="coste" required name="coste" placeholder="10"' . "value='$article[Cost]'>";
                                }
                                else {
                                    echo '<input type="number" step="any" id="coste" required name="coste" placeholder="20">';
                                }
                            ?>
                        </div>

                        <div>
                            <label for="precio">Precio</label>
                            <?php 
                                if($type == "modify" || $type == "delete"){
                                    echo '<input type="number" step="any" id="precio" required name="precio" placeholder="20"' . "value='$article[Price]'>";
                                }
                                else {
                                    echo '<input type="number" step="any" id="precio" required name="precio" placeholder="10">';
                                }
                            ?>
                           
                        </div>

                    </form>

                    <div>
                        <button class="button" onclick="window.location.href='articulos.php'">Volver</button>
                            <?php 
                                if($type == "add"){//Dependiendo de la acción, se muestra un botón u otro
                                    echo '<button class="button" type="submit" form="formulario">Añadir</button>';
                                }

                                if($type == "modify" ){
                                    echo '<button class="button" type="submit" form="formulario"';
                                    echo '>Modificar</button>';
                                }

                                if($type == "delete"){
                                    echo '<button class="button" type="submit"';
                                    echo "onclick=\"window.location.href='formArticulos.php?delete=success&id=".$id."'\"";
                                    echo '>Borrar</button>';
                                }
                            
                            ?>
                    </div>
                    
        </div>                
    </body>
</html>