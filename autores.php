<?php
require_once 'includes/db.php';
$database = new Database();
$db = $database->getConnection();

$mensaje = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $biografia = $_POST['biografia'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $pais = $_POST['pais'];

    try {
        $query = "INSERT INTO autores (nombre, biografia, fecha_nacimiento, pais) 
                  VALUES (:nombre, :biografia, :fecha_nacimiento, :pais)";
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':biografia', $biografia);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->bindParam(':pais', $pais);

        if ($stmt->execute()) {
            $mensaje = '<div class="alert alert-success">Autor agregado exitosamente.</div>';
        }
    } catch(PDOException $e) {
        $mensaje = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}

// Obtener la lista de autores
$query = "SELECT * FROM autores ORDER BY nombre";
$stmt = $db->prepare($query);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca ITLA - Autores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Biblioteca ITLA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="libros.php">Libros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="autores.php">Autores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contacto.php">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <!-- Formulario para agregar autor -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3>Agregar Nuevo Autor</h3>
                        <?php echo $mensaje; ?>
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>

                            <div class="mb-3">
                                <label for="biografia" class="form-label">Biografía</label>
                                <textarea class="form-control" id="biografia" name="biografia" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                            </div>

                            <div class="mb-3">
                                <label for="pais" class="form-label">País</label>
                                <input type="text" class="form-control" id="pais" name="pais" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Guardar Autor</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Lista de autores -->
            <div class="col-md-8">
                <h2>Autores Registrados</h2>
                <div class="row">
                    <?php while ($autor = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($autor['nombre']); ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        <?php echo htmlspecialchars($autor['pais']); ?>
                                    </h6>
                                    <p class="card-text">
                                        <?php echo htmlspecialchars($autor['biografia']); ?>
                                    </p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            Fecha de nacimiento: 
                                            <?php echo date('d/m/Y', strtotime($autor['fecha_nacimiento'])); ?>
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>