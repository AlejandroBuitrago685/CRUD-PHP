<?php
  require "BaseDatos.php";
  $email = $_POST['email'];
  $name = $_POST['nombre'];
  
  checkLogin($email, $name);
?>