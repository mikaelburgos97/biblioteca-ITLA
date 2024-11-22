<?php
require_once 'includes/db.php';

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $asunto = isset($_POST['asunto']) ? trim($_POST['asunto']) : '';
    $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';

    if (empty($nombre) || empty($correo) || empty($asunto) || empty($comentario)) {
        $mensaje = 'Por favor, complete todos los campos.';
        $tipo_mensaje = 'danger';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'Por favor, ingrese un correo electrónico válido.';
        $tipo_mensaje = 'danger';
    } else {
        try {
            $query = "INSERT INTO contacto (nombre, correo, asunto, comentario) 
                     VALUES (:nombre, :correo, :asunto, :comentario)";
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':asunto', $asunto);
            $stmt->bindParam(':comentario', $comentario);
            
            if ($stmt->execute()) {
                $mensaje = '¡Gracias por contactarnos! Tu mensaje ha sido enviado.';
                $tipo_mensaje = 'success';
                
                // Limpiar el formulario
                $nombre = $correo = $asunto = $comentario = '';
            } else {
                $mensaje = 'Lo sentimos, hubo un error al enviar el mensaje.';
                $tipo_mensaje = 'danger';
            }
        } catch(PDOException $e) {
            $mensaje = 'Error: ' . $e->getMessage();
            $tipo_mensaje = 'danger';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca ITLA - Contacto</title>
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
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="libros.php">Libros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="autores.php">Autores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contacto.php">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Contáctanos</h1>
        
        <?php if (!empty($mensaje)) : ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>" role="alert">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="row mt-4">
            <div class="col-md-6">
                <form method="POST" action="contacto.php" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>" required>
                        <div class="invalid-feedback">
                            Por favor ingrese su nombre.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" 
                               value="<?php echo isset($correo) ? htmlspecialchars($correo) : ''; ?>" required>
                        <div class="invalid-feedback">
                            Por favor ingrese un correo electrónico válido.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto</label>
                        <input type="text" class="form-control" id="asunto" name="asunto" 
                               value="<?php echo isset($asunto) ? htmlspecialchars($asunto) : ''; ?>" required>
                        <div class="invalid-feedback">
                            Por favor ingrese el asunto.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="comentario" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="comentario" name="comentario" 
                                  rows="5" required><?php echo isset($comentario) ? htmlspecialchars($comentario) : ''; ?></textarea>
                        <div class="invalid-feedback">
                            Por favor ingrese su mensaje.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
                </form>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Información de Contacto</h5>
                        <p class="card-text">
                            <strong>Dirección:</strong><br>
                            Av. Las Américas #2, La Caleta<br>
                            Boca Chica, República Dominicana<br><br>
                            <strong>Teléfono:</strong><br>
                            (809) 738-4852<br><br>
                            <strong>Email:</strong><br>
                            info@itla.edu.do<br><br>
                            <strong>Horario:</strong><br>
                            Lunes a Viernes: 8:00 AM - 8:00 PM<br>
                            Sábado: 8:00 AM - 12:00 PM
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white mt-5 py-3">
        <div class="container text-center">
            <p>Biblioteca ITLA &copy; <?php echo date('Y'); ?></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Validación del formulario usando Bootstrap
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
    </script>
</body>
</html>