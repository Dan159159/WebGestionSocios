<?php
//Varibles globales para la conexion
$manager = new MongoDB\Driver\Manager("mongodb://root:password@mongo:27017");
$bulk = new MongoDB\Driver\BulkWrite;

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
// Obtener Socios
function getSocios() {
    global $manager;  // Acceder a la variable global $manager
      $pipeline = [
          [
              '$project' => [
                  '_id' => '$_id',
                  'idSocio' => '$idSocio',
                  'nombre' => '$nombre'
              ]
          ]
      ];
  
      $command = new MongoDB\Driver\Command([
          'aggregate' => 'usuarios',
          'pipeline' => $pipeline,
          'cursor' => new stdClass,
      ]);
  
      $cursor = $manager->executeCommand('db_polideportivos', $command);
  
      $socios = [];
      foreach ($cursor as $document) {
          $socios[] = [
              '_id' => $document->_id,
              'idSocio' =>  $document->idSocio,
              'nombre' => $document->nombre
          ];
      }
  
      return $socios;
  }
  $socios = getSocios();
  // Insertar entrada
  function insertarEntrada($idSocio, $idPolideportivo, $fecha, $horaInicio) {
    global $manager;  
    global $bulk;
    $documento = [
        'idSocio' => $idSocio,
        'idPolideportivo' => $idPolideportivo,
        'fecha' => $fecha,
        'horaInicio' => $horaInicio
    ];
    $bulk->insert($documento);
    $manager->executeBulkWrite('db_polideportivos.asistencia', $bulk);
  }
  $manager = new MongoDB\Driver\Manager("mongodb://root:password@mongo:27017");
$bulk = new MongoDB\Driver\BulkWrite;
//Query 1
function getSociosPorPolideportivo() {
    global $manager;

    $pipeline = [
        [
            '$lookup' => [
                'from' => 'usuarios',
                'localField' => 'idSocio',
                'foreignField' => 'idSocio',
                'as' => 'usuario'
            ]
        ],
        [
            '$unwind' => '$usuario'
        ],
        [
            '$group' => [
                '_id' => [
                    'idPolideportivo' => '$idPolideportivo',
                    'idSocio' => '$idSocio'
                ],
                'CantEntrada' => ['$sum' => 1],
                'nombre' => ['$first' => '$usuario.nombre']
            ]
        ],
        [
            '$sort' => ['CantEntrada' => -1]
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
        $sociosPorpolideportivos[] = [
            'idPolideportivo' => $idPolideportivo,
            'CantEntrada' => $document->CantEntrada,
            'idSocio' =>$idSocio,
            'nombre' => $document->nombre
        ];
    }

    return $sociosPorpolideportivos;
}

$sociosPorpolideportivos = getSociosPorPolideportivo();



?>