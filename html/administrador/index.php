<?php

require_once('metodosAdmin.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'];
    $password = $_POST['password'];
    comparar($user, $password);
  }
$titulo = "Sign Up!!";
ob_start();
include('./views/seguridad.html');
$child = ob_get_clean();
include('./views/app.html');

?>
