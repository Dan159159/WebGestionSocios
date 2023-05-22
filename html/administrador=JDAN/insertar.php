<?php
// Varibales globales
$manager = new MongoDB\Driver\Manager("mongodb://root:password@mongo:27017");
$bulk = new MongoDB\Driver\BulkWrite;

// Obetener Socios
function getSocios() {
  global $manager;  // Acceder a la variable global $manager
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
  global $manager;  // Acceder a la variable global $manager
  global $bulk;  // Acceder a la variable global $bulk

  $filtro = ['idSocio' => $idSocio];
  $bulk->delete($filtro);
  $manager->executeBulkWrite('db_polideportivos.usuarios', $bulk);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $idSocio = $_POST['idSocio'];
  $nombre = $_POST['nombre'];
  $edad = $_POST['edad'];
  $genero = $_POST['genero'];

  insertarSocio($idSocio, $nombre, $edad, $genero);
  // No funciona
  echo "<script>alert('Inserción exitosa');</script>";
}
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['eliminar'])) {
  $idSocioEliminar = $_GET['eliminar'];

  eliminarSocio($idSocioEliminar);

  echo "<script>alert('Eliminacion exitosa');</script>";
}
$titulo = 'Bienvenido Administrador';   
include('./index.html');

?>
