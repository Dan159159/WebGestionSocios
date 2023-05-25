<?php
require_once('metodos.php');

// Definir child y titulo
$titulo = "Querys";
ob_start();
include('./views/querys.html');
$child = ob_get_clean();
include('./views/app.html');
?>