<?php
require_once('metodosAdmin.php');
// Cambios en el formulario
$id = $_GET['id'] ?? null;

if ($id) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $idSocio = $_POST['idSocio'];
      $nombre = $_POST['nombre'];
      $edad = $_POST['edad'];
      $genero = $_POST['genero'];
  // Ejcutar  actualizar Socio
      actualizarSocio($idSocio, $nombre, $edad, $genero);
  }
// Ejcutar getSocioById 
  $socios = getSocioById($id);

    if (!empty($socios)) {
        $socio = $socios[0];
        //Incluir html     
        $titulo = 'Welcome Admin time to Edit';
        ob_start();
        include('./views/editar.html');
        $child = ob_get_clean();
        include('./views/app.html');

    } else {
        echo "No se encontró el socio con el ID especificado.";
    }
}
?>
