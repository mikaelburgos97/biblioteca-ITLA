<?php
require_once 'includes/db.php';
$database = new Database();
$db = $database->getConnection();

$mensaje = '';

// Obtener lista de autores para el select
$query_autores = "SELECT id, nombre FROM autores ORDER BY nombre";
$stmt_autores = $db->prepare($query_autores);
$stmt_autores->execute();
$autores = $stmt_autores->fetchAll(PDO::FETCH_ASSOC);

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $autor_id = $_POST['autor_id'];
    $descripcion = $_POST['descripcion'];
    $isbn = $_POST['isbn'];
    $publicado_year = $_POST['publicado_year'];

    try {
        $query = "INSERT INTO libros (titulo, autor_id, descripcion, isbn, publicado_year) 
                  VALUES (:titulo, :autor_id, :descripcion, :isbn, :publicado_year)";
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':autor_id', $autor_id);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':isbn', $isbn);
        $stmt->bindParam(':publicado_year', $publicado_year);

        if ($stmt->execute()) {
            $mensaje = '<div class="alert alert-success">Libro agregado exitosamente.</div>';
        }
    } catch(PDOException $e) {
        $mensaje = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}

// Obtener todos los libros con información del autor
$query = "SELECT l.*, a.nombre as autor_nombre 
          FROM libros l 
          INNER JOIN autores a ON l.autor_id = a.id 
          ORDER BY l.titulo";
$stmt = $db->prepare($query);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca ITLA - Libros</title>
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
                        <a class="nav-link active" href="libros.php">Libros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="autores.php">Autores</a>
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
            <!-- Formulario para agregar libro -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3>Agregar Nuevo Libro</h3>
                        <?php echo $mensaje; ?>
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>

                            <div class="mb-3">
                                <label for="autor_id" class="form-label">Autor</label>
                                <select class="form-control" id="autor_id" name="autor_id" required>
                                    <option value="">Seleccione un autor</option>
                                    <?php foreach ($autores as $autor): ?>
                                        <option value="<?php echo $autor['id']; ?>">
                                            <?php echo htmlspecialchars($autor['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="isbn" class="form-label">ISBN</label>
                                <input type="text" class="form-control" id="isbn" name="isbn" required>
                            </div>

                            <div class="mb-3">
                                <label for="publicado_year" class="form-label">Año de Publicación</label>
                                <input type="number" class="form-control" id="publicado_year" name="publicado_year" 
                                       min="1800" max="<?php echo date('Y'); ?>" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Guardar Libro</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Lista de libros -->
            <div class="col-md-8">
                <h2>Libros Disponibles</h2>
                <div class="row">
                    <?php while ($libro = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($libro['titulo']); ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        Por <?php echo htmlspecialchars($libro['autor_nombre']); ?>
                                    </h6>
                                    <p class="card-text">
                                        <?php echo htmlspecialchars($libro['descripcion']); ?>
                                    </p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            ISBN: <?php echo htmlspecialchars($libro['isbn']); ?><br>
                                            Año: <?php echo htmlspecialchars($libro['publicado_year']); ?>
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