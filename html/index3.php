<?php

function getPolideportivos() {
    $manager = new MongoDB\Driver\Manager("mongodb://root:password@mongo:27017");

    $pipeline = [
        [
            '$project' => [
                '_id' => 0,
                'nombre' => '$nombre',
                'localizacion' => '$localizacion',
                'tlf' => '$tlf',
                'img' => '$img'
            ]
        ]
    ];

    $command = new MongoDB\Driver\Command([
        'aggregate' => 'polideportivos',
        'pipeline' => $pipeline,
        'cursor' => new stdClass,
    ]);

    $cursor = $manager->executeCommand('db_polideportivos', $command);

    $polideportivos = [];
    foreach ($cursor as $document) {
        $polideportivos[] = [
            'nombre' => $document->nombre,
            'localizacion' => $document->localizacion,
            'tlf' => $document->tlf,
            'img' => $document->img
        ];
    }

    return $polideportivos;
}

// Obtener los datos de los polideportivos
$polideportivos = getPolideportivos();

require_once("app_class.php");
require_once('template.class.php');

//new App();
$uri = $_SERVER['REQUEST_URI'];
$uriParts = explode('/', $uri);
array_shift($uriParts);



switch ($uri) {
    case '/':
        ob_start();
        include('./views/index.html');
        $titulo = "bhjcdsbhkldfsdbhlcvbhj"
        $child = ob_get_clean();   
        break;
    
    case '/crud':
        ob_start();
        include('./crud/index.html');
        $child = ob_get_clean();   
        break;
    
}

/*
switch ($uri) {
    case '/':
        include("./views/app.html");
        $childIndex = file_get_contents('./view/index.html');
        $viewIndex = new Template('./views/app.html', [
            "titulo" => "Polideportivos JDaz",
            "child" => $childIndex
        ]);
    
    case '/crud':
        ob_start();
        include('./crud/index.html');
        $child = ob_get_clean();   
        break;
}*/

// Los case llevan a directorios no a  la direccion de Uri como un string
//$titulo = "Polideportivos JDaz";
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


