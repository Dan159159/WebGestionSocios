<?php

// Función para obtener los datos de los polideportivos
function getPolideportivos() {
    $manager = new MongoDB\Driver\Manager("mongodb://root:password@mongo:27017");

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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Polideportivos</title>
    <link rel="stylesheet" href="ranking.css" />
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
            width: 300px;
            background-color: #f5f5f5;
        }

        .card h3 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .card p {
            margin: 0;
            font-size: 14px;
            line-height: 1.5;
        }

        .card img {
            width: 100%;
            height: auto;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div id="app">
        <h1>Polideportivos</h1>
        <a href="../index.html" class="btn">Volver al menú inicio</a>

        <div class="card-container">
            <?php foreach ($polideportivos as $polideportivo) { ?>
                <div class="card">
                    <img src="<?php echo $polideportivo['img']; ?>" alt="Imagen del polideportivo" />
                    <h3><?php echo $polideportivo['nombre']; ?></h3>
                    <p><strong>Localización:</strong> <?php echo $polideportivo['localizacion']; ?></p>
                    <p><strong>Teléfono:</strong> <?php echo $polideportivo['tlf']; ?></p>
                    
                </div>
            <?php } ?>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
