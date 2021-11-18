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


	function getProductsOrderBy($column, $sort_order) { //Funciona exactamente igual que getUsersOrderBy()
		$DB = Conectar();
		$sql = sprintf("SELECT * FROM product ORDER BY %s %s", mysqli_real_escape_string($DB,$column), mysqli_real_escape_string($DB,$sort_order));
		$result = mysqli_query($DB, $sql);

		if (mysqli_num_rows($result) !=0) return $result;

        else echo "No se han encontrado resultados en la base de datos";
	}

	function getProducts() { //Función para obtener todos los productos, funciona igual que getUsers()

		$DB = Conectar();
		$sql = "SELECT * FROM product";
		$listado = mysqli_query($DB, $sql);

		if (mysqli_num_rows($listado) !=0) return $listado;
			
        else echo "No se han encontrado resultados en la base de datos";
	}

	function getProductsByID($id) { //Funciona igual que getProducts pero en este caso pasamos el id para obtener un producto por su ID, se lo pasamos a la ocnsulta sql

		$DB = Conectar();
		$sql = sprintf("SELECT * FROM product WHERE ProductID = %s", mysqli_real_escape_string($DB,$id));
		$datos = mysqli_query($DB, $sql);

		if (mysqli_num_rows($datos) !=0) return $datos;
			
        else echo "No se han encontrado resultados en la base de datos";
	}

	function getUsersByID($id) { //Funciona igual que getProductsByID

		$DB = Conectar();
		$sql = sprintf("SELECT * FROM user WHERE UserID = %s", mysqli_real_escape_string($DB,$id));
		$datos = mysqli_query($DB, $sql);

		if (mysqli_num_rows($datos) !=0) return $datos;
			
        else echo "No se han encontrado resultados en la base de datos";
	}



	function getCategory($tabla){ //Función para obtener el nombre de una categoría en lugar de su id, para poder mostrarla correctamente en la tabla
		$DB = Conectar();
			$sql = "SELECT Name FROM category where CategoryID=$tabla[CategoryID]";	 //Obtenemos el ID de la categoría de cada fila de la tabla mediante: $tabla[CategoryID]
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

	function deleteUser($id){
		$DB = Conectar();
		$sql = sprintf("DELETE FROM user WHERE UserID= %s" ,mysqli_real_escape_string($DB,$id));	
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

	function modifyUser($usuario){
		$DB = Conectar();
		$sql = sprintf("UPDATE user SET UserID=%s, FullName='%s', Email='%s', Password='%s', LastAccess='%s', Enabled=%s WHERE UserID=%s"
		,mysqli_real_escape_string($DB,$usuario['UserID'])
		,mysqli_real_escape_string($DB,$usuario['FullName'])
		,mysqli_real_escape_string($DB,$usuario['Email'])
		,mysqli_real_escape_string($DB,$usuario['Password'])
		,mysqli_real_escape_string($DB,$usuario['LastAccess'])
		,mysqli_real_escape_string($DB,$usuario['Enabled'])
		,mysqli_real_escape_string($DB,$usuario['UserID']));	
		$user = mysqli_query($DB, $sql);

		if ($user === TRUE) return true;

		else{
			echo mysqli_error($DB);
			return false;
		}
	}

	function addProduct($articulo){
		$DB = Conectar();
		$sql = sprintf("INSERT INTO product (ProductID, Name, Cost, Price, CategoryID) VALUES (NULL, '%s', %s, %s, %s)"
		,mysqli_real_escape_string($DB,$articulo['Name'])
		,mysqli_real_escape_string($DB,$articulo['Cost'])
		,mysqli_real_escape_string($DB,$articulo['Price'])
		,mysqli_real_escape_string($DB,$articulo['CategoryID']));
		$product = mysqli_query($DB, $sql);

		if ($product === TRUE) return true;

		else{
			echo mysqli_error($DB);
			return false;
		}
	}

	function addUser($user){
		$DB = Conectar();
		$sql = sprintf("INSERT INTO user (UserID, FullName, Email, Enabled, LastAccess, Password) VALUES (NULL, '%s', '%s', %s, '%s','%s')"
		,mysqli_real_escape_string($DB,$user['FullName'])
		,mysqli_real_escape_string($DB,$user['Email'])
		,mysqli_real_escape_string($DB,$user['Enabled'])
		,mysqli_real_escape_string($DB,$user['LastAccess'])
		,mysqli_real_escape_string($DB,$user['Password']));
		$users = mysqli_query($DB, $sql);

		if ($users === TRUE) return true;

		else{
			echo mysqli_error($DB);
			return false;
		}

	}

	function getSuperAdmins($email){ //Función similar a isSuperAdmin
		$DB = Conectar();
		$sql = sprintf("SELECT SuperAdmin FROM setup WHERE SuperAdmin = (SELECT UserID FROM user WHERE Email='%s')", mysqli_real_escape_string($DB,$email));
		$result = mysqli_query($DB, $sql);
		$rol = "admin";

		if (mysqli_num_rows($result) != 0){

			$_SESSION["rol"] = $rol; //Si el usuario es SuperAdmin le asignamos el rol
			return $result;

		} 

        else echo "No es admin";

	}


	function isAuthorized($email){ //Funciona igual que getSuperAdmins, sin embargo aquí es para comprobar y asignar el rol de autorizado
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

	function isSuperAdmin($user){ //Función para comprobar si un usuario es SuperAdmin
		$DB = Conectar();
		$sql = sprintf("SELECT SuperAdmin FROM setup WHERE SuperAdmin = (SELECT UserID FROM user WHERE Email='%s')" , mysqli_real_escape_string($DB,$user['Email']));
		$result = mysqli_query($DB, $sql);

		if (mysqli_num_rows($result) != 0){
			return true; //Retornamos true o false dependiendo de lo que nos devuelva la consulta
		} 

        else return false;
	}

	function listaCategorias(){ //Función para rellenar el select con las categorías de la base de datos
		$DB = Conectar();
		$sql = "SELECT * FROM category"; //Obtenemos todo de categorías
		$listado = mysqli_query($DB, $sql);

		if (mysqli_num_rows($listado) !=0){

			while($categorias = mysqli_fetch_assoc($listado)){ //De esta manera mostramos el nombre en lugar del ID de la categoría
				echo "<option value='" . $categorias["CategoryID"] . "'>" . $categorias["Name"] . "</option>";
			}

		}

		else echo "<option value='" . "Empty" . "'>" . "No hay datos" . "</option>"; //Si no hay datos lo mostramos para dar feedback al usuario
	}

	function listaCategoriasSelected($id){ //Función para determinar la categoría del producto a la hora de borrar y modificar un producto
		$DB = Conectar();
		$sql = "SELECT * FROM category"; //Obtenemos todo de categorías
		$listado = mysqli_query($DB, $sql);

		if (mysqli_num_rows($listado) !=0){

			while($categorias = mysqli_fetch_assoc($listado)){ //De esta manera mostramos el nombre en lugar del ID de la categoría

				if($id == $categorias["CategoryID"]) echo "<option selected value='" . $categorias["CategoryID"] . "'>" . $categorias["Name"] . "</option>"; //Ponemos la propiedad selected para dejar la categoría del producto seleccionada

				else echo "<option value='" . $categorias["CategoryID"] . "'>" . $categorias["Name"] . "</option>";

			}

		}

		else echo "<option value='" . "Empty" . "'>" . "No hay datos" . "</option>"; //Si no hay datos lo mostramos para dar feedback al usuario
	}

	

	function checkLogin($email, $name){ //Función para loguearse, se pasan los dos parámetros introducidos en el formulario

		if($name != null && $email != null){ //Comprobamos que los campos no estén vacios

			$DB = Conectar();
			$actualDate = date("Y-m-d"); //Obtenemos la fecha actual
			//Realizo así la consulta para evitar inyecciones SQL
			$sql = sprintf("SELECT * FROM user WHERE Email='%s' AND FullName='%s'", mysqli_real_escape_string($DB,$email), mysqli_real_escape_string($DB,$name)); //Comprobamos si los datos existen el la base de datos
			$update = sprintf("UPDATE user SET LastAccess = '$actualDate' WHERE Email='%s'", mysqli_real_escape_string($DB,$email)); //Consulta para actualizar la fecha de último acceso
			$result = mysqli_query($DB, $sql);

			if (mysqli_num_rows($result) != 0){ //Si ha columnas afectadas en la base de datos hacemos lo siguiente
				
				session_start(); //Iniciamos la sesión y creamos las variables correspondientes
				$_SESSION["email"] = $email;
				$_SESSION["userName"] = $name;
				$_SESSION["rol"] = "registrado"; //Rol de usuario corriente

				//Comprobamos si el usuario que se ha logueado es autorizado o resgistrado, en ese caso su rol cambiará
				getSuperAdmins($email);
				isAuthorized($email);
				mysqli_query($DB, $update);

				header("location: ../index.php?logued=true"); //Indicamos que se ha logueado satisfactoriamente
				
				return $result;

			} else header("location: ../index.php?credentials=true"); //En caso de que no haya coincidencias en la base de datos con los datos introducidos se lo indicamos al usuario

		} else header("location: ../index.php?nofields=true"); //Los campos están vacios
		
	}

	function logout(){ //Función para cerrar sesión
		if(!isset($_SESSION)) session_start(); //Iniciamos la sesión si no lo estaba
		if(isset($_SESSION["email"]) && isset($_SESSION["userName"])){ //Si existen las variables de sesión hacemos lo siguiente
	
		   unset($_SESSION["email"]); //Las desasignamos
		   unset($_SESSION["userName"]);

		   if(isset($_SESSION["rol"])) unset($_SESSION["rol"]); //Si también existía un rol, lo eliminamos también (Por mera seguridad)
		   
	   }
	}

?>