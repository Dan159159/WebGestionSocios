<?php
// Obenter socio segun el ID
function getSocioById($id) {
  $manager = new MongoDB\Driver\Manager("mongodb://root:password@mongo:27017");

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
  $manager = new MongoDB\Driver\Manager("mongodb://root:password@mongo:27017");

  $bulk = new MongoDB\Driver\BulkWrite;

  $filtro = ['idSocio' => $idSocio];

  $actualizacion = ['$set' => ['nombre' => $nombre, 'edad' => $edad, 'genero' => $genero]];

  $bulk->update($filtro, $actualizacion);

  $manager->executeBulkWrite('db_polideportivos.usuarios', $bulk);
}
// Cambios en el formulario
// Ejcutar  actualizar Socio
$id = $_GET['id'] ?? null;

if ($id) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $idSocio = $_POST['idSocio'];
      $nombre = $_POST['nombre'];
      $edad = $_POST['edad'];
      $genero = $_POST['genero'];

      actualizarSocio($idSocio, $nombre, $edad, $genero);
  }
// Ejcutar getSocioById 
  $socios = getSocioById($id);

    if (!empty($socios)) {
        $socio = $socios[0];
        //Incluir html     
        $titulo = 'Welcome Admin time to Edit';
        $child = "editar.html";
        include('./views/app.html');

    } else {
        echo "No se encontró el socio con el ID especificado.";
    }
}
?>
