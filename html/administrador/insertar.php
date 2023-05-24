<?php
require_once('metodosAdmin.php');
// Recibir formulario 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $idSocio = $_POST['idSocio'];
  $nombre = $_POST['nombre'];
  $edad = $_POST['edad'];
  $genero = $_POST['genero'];

  insertarSocio($idSocio, $nombre, $edad, $genero);
}
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['eliminar'])) {
  $idSocioEliminar = $_GET['eliminar'];

  eliminarSocio($idSocioEliminar);
}
// Incluir app.hmtl e index.html 
$titulo = 'Bienvenido Administrador';
ob_start();
include('./views/index.html');
$child = ob_get_clean();
include('./views/app.html');

?>
