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
        ?>
        <!doctype html>
        <html lang="en">
        
        <head>
          <title><?= $titulo ?></title>
          <!-- Required meta tags -->
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
          <!-- Bootstrap CSS v5.2.1 -->
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
        
          <style>
            body {
              display: flex;
              justify-content: center;
              align-items: center;
              min-height: 100vh;
              background-color:rgb(122, 166, 255);
            }
        
            main {
              width: 400px;
              color: black;
            }
            footer>{
              color:black;
            }
          </style>
        </head>
        
        <body>
        
        
          <main>
            <form action="" method="post">
              <div class="mb-3">
                <label for="idSocio" class="form-label">Membership card:</label>
                <input type="text" class="form-control" id="idSocio" name="idSocio" value="<?= $socio['idSocio'] ?>">
              </div>
              <div class="mb-3">
                <label for="nombre" class="form-label">Name:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $socio['nombre'] ?>">
              </div>
              <div class="mb-3">
                <label for="edad" class="form-label">Age:</label>
                <input type="text" class="form-control" id="edad" name="edad" value="<?= $socio['edad'] ?>">
              </div>
              <div class="mb-3">
                <label for="genero" class="form-label">Gender:</label>
                <input type="text" class="form-control" id="genero" name="genero" value="<?= $socio['genero'] ?>">
              </div>
              <button type="submit" class="btn btn-primary">Save</button>
            </form>
          </main>

<footer>
  <!-- place footer here -->
  <div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
      <div class="col-md-4 d-flex align-items-center">
        <a href="/" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
          <svg class="bi" width="30" height="24"><use xlink:href="#bootstrap"></use></svg>
        </a>
        <span class="text-muted">© 2021 Company, Inc</span>
      </div>
  
      <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
        <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#twitter"></use></svg></a></li>
        <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#instagram"></use></svg></a></li>
        <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#facebook"></use></svg></a></li>
      </ul>
    </footer>
  </div>
</footer>
<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
  integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
  integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
</script>
</body>

</html>   


        <?php
    } else {
        echo "No se encontró el socio con el ID especificado.";
    }
}
?>
