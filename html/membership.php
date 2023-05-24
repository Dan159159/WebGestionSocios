<?php
require_once('metodos.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idSocio = $_POST['idSocio'];
    $idPolideportivo = $_POST['idPolideportivo'];
    $fecha = $_POST['fecha'];
    $horaInicio = $_POST['horaInicio'];
  
    insertarEntrada($idSocio, $idPolideportivo, $fecha, $horaInicio);
}

$titulo = "Polideportivos Donostia JDAZ";
ob_start();
include('./views/membership.html');
$child = ob_get_clean();
include('./views/app.html');
?>