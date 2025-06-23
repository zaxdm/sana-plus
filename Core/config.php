<?php  
    // Datos de conexión a PostgreSQL en Render
    const DB_HOST = getenv('DB_HOST');
    const DB_PORT = getenv('DB_PORT'); // PostgreSQL necesita el puerto
    const DB_NAME = getenv('DB_NAME');
    const DB_USER = getenv('DB_USER');
    const DB_PASSWORD = getenv('DB_PASSWORD');

    // Cadena de conexión para PostgreSQL (ojo con 'pgsql')
    const SGBD = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;

    // Datos para encriptación
    const METHOD = "AES-256-CBC";
    const SECRET_KEY = '$#*ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890#*';
    const SECRET_IV = '20001109108103975194753';

    try {
        $conexion = new PDO(SGBD, DB_USER, DB_PASSWORD);
        $conexion->exec("SET NAMES 'UTF8'");
        //echo "✅ Conexión exitosa a PostgreSQL";
    } catch (Exception $e) {
        die("❌ Error de conexión: " . $e->getMessage());
    }
?>
