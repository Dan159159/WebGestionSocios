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
function getSociosPorPolideportivo() {
    global $manager;
    $pipeline = [
        [
            '$lookup' => [
                'from' => 'polideportivos',
                'localField' => 'idPolideportivo',
                'foreignField' => '_id',
                'as' => 'polideportivo'
            ]
        ],
        [
            '$unwind' => '$polideportivo'
        ],
        [
            '$lookup' => [
                'from' => 'usuarios',
                'localField' => 'idSocio',
                'foreignField' => '_id',
                'as' => 'socio'
            ]
        ],
        [
            '$unwind' => '$socio'
        ],
        [
            '$project' => [
                '_id' => 0,
                'socio.nombre' => 1,
                'polideportivo.nombre' => 1
            ]
        ]
    ];

    $command = new MongoDB\Driver\Command([
        'aggregate' => 'asistencia',
        'pipeline' => $pipeline,
        'cursor' => new stdClass,
    ]);

    $cursor = $manager->executeCommand('db_polideportivos', $command);

    $sociosPorpolideportivos = [];
    foreach ($cursor as $document) {
        $result[] = [
            'nombre_socio' => $document->socio->nombre,
            'nombre_polideportivo' => $document->polideportivo->nombre
        ];
    }

    return $sociosPorpolideportivos;

}
$sociosPorpolideportivos = getSociosPorPolideportivo();
//Request URI
$uri = $_SERVER['REQUEST_URI'];
$uriParts = explode('/', $uri);
array_shift($uriParts);

$titulo = "Título de la página";

switch ($uri) {
    case '/':
        $child = "membership.html";
        include('./views/app.html');
        break;
    case '/crud':
        $child = "index.html";
        include('./views/app.html');
        break;
    default:
        //Code
        break;
}


?>