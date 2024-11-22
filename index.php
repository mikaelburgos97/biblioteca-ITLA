<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca ITLA - Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
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
                        <a class="nav-link active" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="libros.php">Libros</a>
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
        <div class="jumbotron">
            <h1 class="display-4">Bienvenido a la Biblioteca ITLA</h1>
            <p class="lead">Explora nuestra colección de libros y conoce a nuestros autores.</p>
            <hr class="my-4">
            <p>Encuentra tu próxima lectura favorita entre nuestra selección.</p>
            <a class="btn btn-primary btn-lg" href="libros.php" role="button">Ver Libros</a>
        </div>

        <?php
        require_once 'includes/db.php';
        $database = new Database();
        $db = $database->getConnection();

        // Obtener últimos libros añadidos
        $query = "SELECT l.*, a.nombre as autor_nombre FROM libros l 
                 INNER JOIN autores a ON l.autor_id = a.id 
                 ORDER BY l.created_at DESC LIMIT 3";
        $stmt = $db->prepare($query);
        $stmt->execute();
        ?>

        <h2 class="mt-5 mb-4">Últimos libros añadidos</h2>
        <div class="row">
            <?php while ($libro = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($libro['titulo']); ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                <?php echo htmlspecialchars($libro['autor_nombre']); ?>
                            </h6>
                            <p class="card-text">
                                <?php echo htmlspecialchars(substr($libro['descripcion'], 0, 100)) . '...'; ?>
                            </p>
                            <a href="libros.php" class="btn btn-primary">Ver más</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer class="bg-dark text-white mt-5 py-3">
        <div class="container text-center">
            <p>Biblioteca ITLA &copy; <?php echo date('Y'); ?></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>