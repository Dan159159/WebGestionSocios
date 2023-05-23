<?php
$manager = new MongoDB\Driver\Manager("mongodb://root:password@mongo:27017");
function getPolideportivos() {
    global $manager;
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

$uri = $_SERVER['REQUEST_URI'];
$uriParts = explode('/', $uri);
array_shift($uriParts);

$titulo = "Título de la página";

switch ($uri) {
    case '/':
        $child = "index.html";
        include('./views/app.html');
        break;
    case '/membership.html':
        $child = "membership.html";
        include('./views/app.html');
        break;
    default:
        //Code
        break;
}


?>