<?php

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

function actualizarSocio($idSocio, $nombre, $edad, $genero) {
  $manager = new MongoDB\Driver\Manager("mongodb://root:password@mongo:27017");

  $bulk = new MongoDB\Driver\BulkWrite;

  $filtro = ['idSocio' => $idSocio];

  $actualizacion = ['$set' => ['nombre' => $nombre, 'edad' => $edad, 'genero' => $genero]];

  $bulk->update($filtro, $actualizacion);

  $manager->executeBulkWrite('db_polideportivos.usuarios', $bulk);
}

$id = $_GET['id'] ?? null;

if ($id) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $idSocio = $_POST['idSocio'];
      $nombre = $_POST['nombre'];
      $edad = $_POST['edad'];
      $genero = $_POST['genero'];

      actualizarSocio($idSocio, $nombre, $edad, $genero);

      // Redireccionar o mostrar un mensaje de éxito
      // header("Location: archivo_de_exito.php");
      // echo "Actualización exitosa";
  }

  $socios = getSocioById($id);

    if (!empty($socios)) {
        $socio = $socios[0];     
        include('./views/editar.html');

    } else {
        echo "No se encontró el socio con el ID especificado.";
    }
}
?>
