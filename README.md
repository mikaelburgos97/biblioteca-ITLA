Proyecto Final - Biblioteca Online ITLA
Enlaces del Proyecto

Sitio Web: biblioteca-ila.lovestoblog.com


Descripción del Proyecto
Sistema de gestión de biblioteca online desarrollado con PHP y MySQL, que permite consultar libros y autores, además de un sistema de contacto.
Tecnologías Utilizadas

HTML5
CSS3 (Bootstrap 5)
JavaScript
PHP
MySQL
PDO para conexiones a base de datos

Estructura del Proyecto
Copyproyecto-biblioteca/
├── includes/
│   └── db.php           # Configuración de la conexión a la base de datos
├── index.php            # Página principal
├── libros.php          # Gestión y visualización de libros
├── autores.php         # Gestión y visualización de autores
└── contacto.php        # Formulario de contacto
Funcionalidades Principales
1. Gestión de Autores

Añadir nuevos autores
Ver listado de autores registrados
Campos: nombre, biografía, fecha de nacimiento, país

2. Gestión de Libros

Añadir nuevos libros
Asociar libros con autores
Ver catálogo de libros
Campos: título, autor, descripción, ISBN, año de publicación

3. Sistema de Contacto

Formulario de contacto
Almacenamiento de mensajes en base de datos

Estructura de la Base de Datos
Tabla: autores
sqlCopyCREATE TABLE autores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    biografia TEXT,
    fecha_nacimiento DATE,
    pais VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
Tabla: libros
sqlCopyCREATE TABLE libros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(200) NOT NULL,
    autor_id INT,
    descripcion TEXT,
    isbn VARCHAR(13),
    publicado_year INT,
    imagen VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (autor_id) REFERENCES autores(id)
);
Tabla: contacto
sqlCopyCREATE TABLE contacto (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    asunto VARCHAR(200) NOT NULL,
    comentario TEXT NOT NULL
);
Instrucciones de Uso

Página Principal

Muestra los últimos libros añadidos
Navegación a todas las secciones


Sección de Autores

Formulario para añadir nuevos autores
Visualización de autores registrados


Sección de Libros

Formulario para añadir nuevos libros
Catálogo completo de libros disponibles
Selección de autor desde lista desplegable


Sección de Contacto

Formulario para enviar mensajes
Validación de campos



Aspectos Técnicos

Uso de PDO para conexiones seguras a la base de datos
Implementación de Bootstrap para diseño responsivo
Validación de formularios tanto del lado del cliente como del servidor
Protección contra inyección SQL mediante consultas preparadas
Manejo de errores y mensajes de feedback al usuario

Requerimientos del Sistema

PHP 7.4 o superior
MySQL 5.7 o superior
Servidor web con soporte PHP (Apache/Nginx)
Extensión PDO habilitada

Desarrollador

Nombre: Mikael Burgos Vasquez
ID Estudiante: 2020-9410
Asignatura: Programación Web
Profesor: Prof. Daniel Parra


Nota: Este proyecto fue desarrollado como parte del curso de Programación Web en ITLA, cumpliendo con los requerimientos especificados en la asignación del proyecto final.
