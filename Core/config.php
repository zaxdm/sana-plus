<?php  
    // Datos de conexión a PostgreSQL en Render
    const DB_HOST = 'tu_host_aqui';
    const DB_PORT = 'tu_puerto_aqui';
    const DB_NAME = 'tu_db_aqui';
    const DB_USER = 'tu_usuario_aqui';
    const DB_PASSWORD = 'tu_contraseña_aqui';

    // Esto debe ser con define o variable
    $SGBD = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;

    // Datos para encriptación
    const METHOD = "AES-256-CBC";
    const SECRET_KEY = '$#*ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890#*';
    const SECRET_IV = '20001109108103975194753';

    try {
        $conexion = new PDO($SGBD, DB_USER, DB_PASSWORD);
        $conexion->exec("SET NAMES 'UTF8'");
        // echo "✅ Conexión exitosa a PostgreSQL";
    } catch (Exception $e) {
        die("❌ Error de conexión: " . $e->getMessage());
    }
?>
