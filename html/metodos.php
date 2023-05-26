<?php
//Varibles globales para la conexion
$manager = new MongoDB\Driver\Manager("mongodb://root:password@mongo:27017");
$bulk = new MongoDB\Driver\BulkWrite;

// Obtener polideportivos
function getPolideportivos() {
    global $manager;
    // Campos polideportivos
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
    // Base de datos
    $command = new MongoDB\Driver\Command([
        'aggregate' => 'polideportivos',
        'pipeline' => $pipeline,
        'cursor' => new stdClass,
    ]);

    $cursor = $manager->executeCommand('db_polideportivos', $command);
    // Guardar datos en Array
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
$polideportivos = getPolideportivos();

// Obtener Socios
function getSocios() {
    global $manager; 
    // Campos usuarios
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
      // Guardar datos en Array
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
  
//Query 1/2
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
            '$project' => [
                'idPolideportivo' => '$_id.idPolideportivo',
                'CantEntrada' => 1,
                'idSocio' => '$_id.idSocio',
                'nombre' => 1
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
            'idPolideportivo' => $document->idPolideportivo,
            'CantEntrada' => $document->CantEntrada,
            'idSocio' => $document->idSocio,
            'nombre' => $document->nombre
        ];
    }

    return $sociosPorpolideportivos;
}

$sociosPorpolideportivos = getSociosPorPolideportivo();

//Query2
function getTodosSociosPorPolideportivo() {
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
            '$project' => [
                '_id' => 0,
                'idPolideportivo' => '$_id.idPolideportivo',
                'CantEntrada' => 1,
                'idSocio' => '$_id.idSocio',
                'nombre' => 1
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

    $todosSociosPorpolideportivos = [];
    foreach ($cursor as $document) {
        $todosSociosPorpolideportivos[] = [
            'idPolideportivo' => $document->idPolideportivo,
            'CantEntrada' => $document->CantEntrada,
            'idSocio' => $document->idSocio,
            'nombre' => $document->nombre
        ];
    }

    return $todosSociosPorpolideportivos;
}

$todosSociosPorpolideportivos = getTodosSociosPorPolideportivo();

// Socios en menos de cinco dias
/*function getSociosMenosCincoDias() {
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
                    'idSocio' => '$idSocio',
                    'month' => ['$month' => '$fecha']
                ],
                'CantEntrada' => ['$sum' => 1],
                'nombre' => ['$first' => '$usuario.nombre']
            ]
        ],
        [
            '$match' => [
                'CantEntrada' => ['$lt' => 5]
            ]
        ],
        [
            '$project' => [
                '_id' => 0,
                'idPolideportivo' => '$_id.idPolideportivo',
                'idSocio' => '$_id.idSocio',
                'nombre' => 1,
                'Mes' => '$_id.month',
                'CantEntrada' => 1
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

    $sociosMenosCincoDias = [];
    foreach ($cursor as $document) {
        $sociosMenosCincoDias[] = [
            'idPolideportivo' => $document->idPolideportivo,
            'idSocio' => $document->idSocio,
            'nombre' => $document->nombre,
            'Mes' => $document->Mes,
            'CantEntrada' => $document->CantEntrada
        ];
    }

    return $sociosMenosCincoDias;
}

$sociosMenosCincoDias = getSociosMenosCincoDias();

*/
?>