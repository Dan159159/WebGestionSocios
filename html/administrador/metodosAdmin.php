<?php
// Varibales globales
$manager = new MongoDB\Driver\Manager("mongodb://root:password@mongo:27017");
$bulk = new MongoDB\Driver\BulkWrite;

// Obetener Socios
function getSocios() {
  global $manager; 
    $pipeline = [
        [
            '$project' => [
                '_id' => '$_id',
                'idSocio' => '$idSocio',
                'nombre' => '$nombre',
                'edad' => '$edad',
                'genero' => '$genero'
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
            'idSocio' => $document->idSocio,
            'nombre' => $document->nombre,
            'edad' => $document->edad,
            'genero' => $document->genero
        ];
    }

    return $socios;
}
$socios = getSocios();

// Insertar Socios
function insertarSocio($idSocio, $nombre, $edad, $genero) {
  global $manager;  // Acceder a la variable global $manager
  global $bulk;  // Acceder a la variable global $bulk
  $documento = [
      'idSocio' => $idSocio,
      'nombre' => $nombre,
      'edad' => $edad,
      'genero' => $genero
  ];
  $bulk->insert($documento);
  $manager->executeBulkWrite('db_polideportivos.usuarios', $bulk);
}

//Eliminar Socios
function eliminarSocio($idSocio) {
  global $manager;  
  global $bulk; 

  $filtro = ['idSocio' => $idSocio];
  $bulk->delete($filtro);
  $manager->executeBulkWrite('db_polideportivos.usuarios', $bulk);
}
// Obenter socio segun el ID
function getSocioById($id) {
    global $manager;
  
    $filter = ['idSocio' => $id];
  
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $manager->executeQuery('db_polideportivos.usuarios', $query);
  
    $socios = [];
    foreach ($cursor as $document) {
        $socios[] = [
            '_id' => $document->_id,
            'idSocio' => $document->idSocio,
            'nombre' => $document->nombre,
            'edad' => $document->edad,
            'genero' => $document->genero
        ];
    }
  
    return $socios;
  }
  // Actualizare socio
  function actualizarSocio($idSocio, $nombre, $edad, $genero) {
    global $manager;
  
    global $bulk;
  
    $filtro = ['idSocio' => $idSocio];
  
    $actualizacion = ['$set' => ['nombre' => $nombre, 'edad' => $edad, 'genero' => $genero]];
  
    $bulk->update($filtro, $actualizacion);
  
    $manager->executeBulkWrite('db_polideportivos.usuarios', $bulk);
  }
  // Obtener admins y su contraseña de bd
  function getAdmins(){
    global $manager;
    $pipeline = [
      [
          '$project' => [
              '_id' => 0,
              'user' => '$user',
              'password' => '$password'
          ]
      ]
  ];

  $command = new MongoDB\Driver\Command([
      'aggregate' => 'admins',
      'pipeline' => $pipeline,
      'cursor' => new stdClass,
  ]);

  $cursor = $manager->executeCommand('db_polideportivos', $command);

  $admins = [];
  foreach ($cursor as $document) {
      $admins[] = [
          'user' => $document->user,
          'password' => $document->password
      ];
  }

  return $admins;
  }
  $admins = getAdmins();

  //Metodo para comprar la user y contra
  function comparar($user, $password) {
  $admins = getAdmins();

  foreach ($admins as $admin) {
    if ($admin['user'] === $user && $admin['password'] === $password) {
      header('Location: insertar.php');
      //echo 'Los datos coinciden.';
      return;
    }
  }

  echo '</br><div>
  <h1 style="color:black;
  background-color:red;
  border-radius:20px;">Error</h1>
  </div>';
}
?>