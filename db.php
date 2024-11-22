<?php
class Database {
    // Asegúrate de usar los datos exactos que te proporciona InfinityFree
    private $host = "sql111.infinityfree.com"; // El host de MySQL
    private $db_name = "if0_37759391_libros";  // Tu nombre de base de datos
    private $username = "if0_37759391";        // Tu usuario de MySQL
    private $password = "tZniSaCsskDJ4xD";                    // La contraseña que te proporcionó InfinityFree

    public function getConnection() {
        try {
            $conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $conn->exec("set names utf8");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            return null;
        }
    }
}
?>