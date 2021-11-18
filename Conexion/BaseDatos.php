<?php

    function Conectar() { //Función para conectar la base de datos

        $host = "localhost";
        $user = "root";
        $password = "";
        $database = "pac3_daw"; //Como la base de datos siempre va a ser la misma se la indico en lugar de pasarlo como parámetro

        $conexion = mysqli_connect($host, $user, $password, $database); //Realizamos la conexión con los datos indicados

        if (!$conexion) { //Si al conexión no se ha establecido indicamos el error al usuario
            die("Error en la conexión con la base de datos" . mysqli_connect_error());
        }	
            return $conexion;
    }

    function getUsers() { //Función para obtener los usuarios de la base de datos
		$DB = Conectar(); //Conectamos con la base de datos
		$sql = "SELECT * FROM user"; //Obtenemos todos los datos del usuario
		$result = mysqli_query($DB, $sql); //Realizamos la consulta especificada

		if (mysqli_num_rows($result) !=0) return $result; //Si ha devuelto columnas retornamos el resultado

        else echo "No se han encontrado usuarios en la base de datos";
	}

	function getUsersOrderBy($column, $sort_order) { //Función para ordenar los datos de una tabla en función del encabezado o columna
		$DB = Conectar(); //Conectamos con la base de datos
		$sql = sprintf("SELECT * FROM user ORDER BY %s %s", mysqli_real_escape_string($DB,$column), mysqli_real_escape_string($DB,$sort_order)); //Pasamos a la consulta los parámetros (encabezado por el que ordenar y si es ASC o DESC)
		$result = mysqli_query($DB, $sql); //Realizamos la consulta especificada

		if (mysqli_num_rows($result) !=0) return $result; //Si ha devuelto columnas retornamos el resultado

        else echo "No se han encontrado resultados en la base de datos";
	}


	function getProductsOrderBy($column, $sort_order) {
		$DB = Conectar();
		$sql = sprintf("SELECT * FROM product ORDER BY %s %s", mysqli_real_escape_string($DB,$column), mysqli_real_escape_string($DB,$sort_order));
		$result = mysqli_query($DB, $sql);

		if (mysqli_num_rows($result) !=0) return $result;

        else echo "No se han encontrado resultados en la base de datos";
	}

	function getProducts() {

		$DB = Conectar();
		$sql = "SELECT * FROM product";
		$listado = mysqli_query($DB, $sql);

		if (mysqli_num_rows($listado) !=0) return $listado;
			
        else echo "No se han encontrado resultados en la base de datos";
	}

	function getProductsByID($id) {

		$DB = Conectar();
		$sql = sprintf("SELECT * FROM product WHERE ProductID = %s", mysqli_real_escape_string($DB,$id));
		$datos = mysqli_query($DB, $sql);

		if (mysqli_num_rows($datos) !=0) return $datos;
			
        else echo "No se han encontrado resultados en la base de datos";
	}



	function getCategory($tabla){
		$DB = Conectar();
			$sql = "SELECT Name FROM category where CategoryID=$tabla[CategoryID]";	
			$category = mysqli_query($DB, $sql);

			if (mysqli_num_rows($category) !=0) return $category;
				
			else echo "No se han encontrado category en la base de datos";
									
	}

	function getCategoryByID($id){
		$DB = Conectar();
		$sql = sprintf("SELECT Name FROM category where CategoryID= %s" ,mysqli_real_escape_string($DB,$id));	
		$category = mysqli_query($DB, $sql) -> fetch_assoc();

		if (mysqli_num_rows($category) !=0) return $category["Name"];
				
		else echo "No se han encontrado category en la base de datos";
									
	}

	function deleteProduct($id){
		$DB = Conectar();
		$sql = sprintf("DELETE FROM product WHERE ProductID= %s" ,mysqli_real_escape_string($DB,$id));	
		$product = mysqli_query($DB, $sql);


		if ($product === TRUE) return true;
				
		else return false;
	}

	function modifyProduct($articulo){
		$DB = Conectar();
		$sql = sprintf("UPDATE product SET ProductID=%s, Name='%s', Cost=%s, Price=%s, CategoryID=%s WHERE ProductID=%s"
		,mysqli_real_escape_string($DB,$articulo['ProductID'])
		,mysqli_real_escape_string($DB,$articulo['Name'])
		,mysqli_real_escape_string($DB,$articulo['Cost'])
		,mysqli_real_escape_string($DB,$articulo['Price'])
		,mysqli_real_escape_string($DB,$articulo['CategoryID'])
		,mysqli_real_escape_string($DB,$articulo['ProductID']));	
		$product = mysqli_query($DB, $sql);

		if ($product === TRUE) return true;

		else{
			echo mysqli_error($DB);
			return false;
		}

	}


	function addProduct($articulo){
		$DB = Conectar();
		$sql1 = "SET FOREIGN_KEY_CHECKS = 0;";
		$sql = sprintf("INSERT INTO product (Name, Cost, Price, CategoryID) VALUES (Name='%s', Cost=%s, Price=%s, CategoryID=%s)"
		,mysqli_real_escape_string($DB,$articulo['Name'])
		,mysqli_real_escape_string($DB,$articulo['Cost'])
		,mysqli_real_escape_string($DB,$articulo['Price'])
		,mysqli_real_escape_string($DB,$articulo['CategoryID']));
		mysqli_query($DB, $sql1);
		$product = mysqli_query($DB, $sql);

		if ($product === TRUE) return true;

		else{
			echo mysqli_error($DB);
			return false;
		}

	}


	function getSuperAdmins($email){
		$DB = Conectar();
		$sql = sprintf("SELECT SuperAdmin FROM setup WHERE SuperAdmin = (SELECT UserID FROM user WHERE Email='%s')", mysqli_real_escape_string($DB,$email));
		$result = mysqli_query($DB, $sql);
		$rol = "admin";


		if (mysqli_num_rows($result) != 0){

			$_SESSION["rol"] = $rol;
			return $result;

		} 

        else echo "No es admin";

	}


	function isAuthorized($email){
		$DB = Conectar();
		$sql = sprintf("SELECT * FROM user WHERE Email= '%s' AND Enabled = 1", mysqli_real_escape_string($DB,$email));
		$result = mysqli_query($DB, $sql);
		$rol = "autorizado";


		if (mysqli_num_rows($result) != 0){

			$_SESSION["rol"] = $rol;
			return true;

		} 

        else return false;

	}

	function isSuperAdmin($user){
		$DB = Conectar();
		$sql = sprintf("SELECT SuperAdmin FROM setup WHERE SuperAdmin = (SELECT UserID FROM user WHERE Email='%s')" , mysqli_real_escape_string($DB,$user['Email']));
		$result = mysqli_query($DB, $sql);

		if (mysqli_num_rows($result) != 0){
			return true;
		} 

        else return false;
	}

	function listaCategorias(){
		$DB = Conectar();
		$sql = "SELECT * FROM category";
		$listado = mysqli_query($DB, $sql);

		if (mysqli_num_rows($listado) !=0){

			while($categorias = mysqli_fetch_assoc($listado)){
				echo "<option value='" . $categorias["CategoryID"] . "'>" . $categorias["Name"] . "</option>";
			}

		}

		else echo "<option value='" . "Empty" . "'>" . "No hay datos" . "</option>";
	}

	function listaCategoriasSelected($id){
		$DB = Conectar();
		$sql = "SELECT * FROM category";
		$listado = mysqli_query($DB, $sql);

		if (mysqli_num_rows($listado) !=0){

			while($categorias = mysqli_fetch_assoc($listado)){

				if($id == $categorias["CategoryID"]) echo "<option selected value='" . $categorias["CategoryID"] . "'>" . $categorias["Name"] . "</option>";

				else echo "<option value='" . $categorias["CategoryID"] . "'>" . $categorias["Name"] . "</option>";

			}

		}

		else echo "<option value='" . "Empty" . "'>" . "No hay datos" . "</option>";
	}

	

	function checkLogin($email, $name){

		if($name != null && $email != null){

			$DB = Conectar();
			$actualDate = date("Y-m-d");
			//Realizo así la consulta para evitar inyecciones SQL
			$sql = sprintf("SELECT * FROM user WHERE Email='%s' AND FullName='%s'", mysqli_real_escape_string($DB,$email), mysqli_real_escape_string($DB,$name));
			$update = sprintf("UPDATE user SET LastAccess = '$actualDate' WHERE Email='%s'", mysqli_real_escape_string($DB,$email));
			$result = mysqli_query($DB, $sql);

			if (mysqli_num_rows($result) != 0){
				
				session_start();
				$_SESSION["email"] = $email;
				$_SESSION["userName"] = $name;
				$_SESSION["rol"] = "registrado";

				getSuperAdmins($email);
				isAuthorized($email);
				mysqli_query($DB, $update);

				header("location: ../index.php?logued=true");
				
				return $result;

			} else header("location: ../index.php?credentials=true");

		} else header("location: ../index.php?nofields=true");
		
	}

	function logout(){
		if(!isset($_SESSION)) session_start(); 
		if(isset($_SESSION["email"]) && isset($_SESSION["userName"])){
	
		   unset($_SESSION["email"]);
		   unset($_SESSION["userName"]);

		   if(isset($_SESSION["rol"])) unset($_SESSION["rol"]);
		   
	   }
	}

?>