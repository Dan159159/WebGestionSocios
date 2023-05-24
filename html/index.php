<?php

require_once('metodos.php');
$titulo = "Polideportivos Donostia JDAZ";
ob_start();
include('./views/index.html');
$child = ob_get_clean();
include('./views/app.html');

?>