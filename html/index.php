<?php

require_once("app_class.php");
require_once('template.class.php');

//new App();
$uri = $_SERVER['REQUEST_URI'];
$uriParts = explode('/', $uri);
array_shift($uriParts);



switch ($uri) {
    case '/':
        ob_start();
        include("./views/index.html");
        $child = ob_get_clean();   
        break;
    
    case '/crud':
        ob_start();
        include("./crud/index.html");
        $child = ob_get_clean();   
        break;
    default:
        echo "NO NO NO";
        break;
}
// Los case llevan a directorios no a  la direccion de Uri como un string
$titulo = "Polideportivos JDaz";
include("./views/app.html");
/*$childIndex = file_get_contents('./view/index.html');
$viewIndex = new Template('./views/app.html', [
    "titulo" => "Polideportivos JDaz",
    "child" => $childIndex
]);

$childAdmin = file_get_contents('./view/admin.html');
$viewAdmin = new Template('./views/app.html', [
    "titulo" => "Bienvenido",
    "child" => $childAdmin
]);
$titulo = "Polideportivos JDaz";
include("./views/app.html");*/



?>


