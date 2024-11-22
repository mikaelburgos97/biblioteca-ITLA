<?php
require_once '../includes/db.php';
$database = new Database();
$db = $database->getConnection();

$mensaje = '';
$autor_id = isset($_GET['autor_id']) ? $_GET['autor_id'] : null;

// Obtener información del autor
if ($autor_id) {
    $query = "SELECT nombre FROM autores WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $autor_id);
    $stmt->execute();
    $autor = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $isbn = $_POST['isbn'];
    $publicado_year = $_POST['publicado_year'];
    $autor_id = $_POST['autor_id'];

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Libro - Biblioteca ITLA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Agregar Nuevo Libro</h1>
        <?php if ($autor): ?>
            <h4>Autor: <?php echo htmlspecialchars($autor['nombre']); ?></h4>
        <?php endif; ?>
        
        <?php echo $mensaje; ?>

        <form method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="autor_id" value="<?php echo htmlspecialchars($autor_id); ?>">
            
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required>
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
            <a href="agregar_autor.php" class="btn btn-secondary">Volver a Autores</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>