<?php
require_once('metodos.php');

// Definir child y titulo
$titulo = "Polideportivos Donostia JDAZ";
ob_start();
include('./views/index.html');
$child = ob_get_clean();
include('./views/app.html');

?>