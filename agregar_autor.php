<?php
require_once '../includes/db.php';
$database = new Database();
$db = $database->getConnection();

$mensaje = '';

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

// Obtener lista de autores para mostrar
$query = "SELECT * FROM autores ORDER BY nombre";
$stmt = $db->prepare($query);
$stmt->execute();
$autores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Autores - Biblioteca ITLA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Gestión de Autores</h1>
        
        <?php echo $mensaje; ?>

        <!-- Formulario para agregar autor -->
        <div class="card mb-4">
            <div class="card-body">
                <h3>Agregar Nuevo Autor</h3>
                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Autor</label>
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
                    <a href="../autores.php" class="btn btn-secondary">Volver</a>
                </form>
            </div>
        </div>

        <!-- Lista de autores registrados -->
        <div class="card">
            <div class="card-body">
                <h3>Autores Registrados</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>País</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($autores as $autor): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($autor['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($autor['pais']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($autor['fecha_nacimiento'])); ?></td>
                                    <td>
                                        <a href="agregar_libro.php?autor_id=<?php echo $autor['id']; ?>" 
                                           class="btn btn-sm btn-primary">Agregar Libro</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>