<?php  
    // Datos de conexión a PostgreSQL en Render
    $DB_HOST = getenv('DB_HOST');
    $DB_PORT = getenv('DB_PORT');
    $DB_NAME = getenv('DB_NAME');
    $DB_USER = getenv('DB_USER');
    $DB_PASSWORD = getenv('DB_PASSWORD');

    // Cadena de conexión para PostgreSQL (ojo con 'pgsql')
    $SGBD = "pgsql:host=" . $DB_HOST . ";port=" . $DB_PORT . ";dbname=" . $DB_NAME;

    // Datos para encriptación (estos sí pueden ser constantes porque son valores fijos)
    const METHOD = "AES-256-CBC";
    const SECRET_KEY = '$#*ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890#*';
    const SECRET_IV = '20001109108103975194753';

    try {
        $conexion = new PDO($SGBD, $DB_USER, $DB_PASSWORD);
        $conexion->exec("SET NAMES 'UTF8'");
        // echo "✅ Conexión exitosa a PostgreSQL";
    } catch (Exception $e) {
        die("❌ Error de conexión: " . $e->getMessage());
    }
?>
