<?php  
	// Datos de conexión a base de datos en InfinityFree
    const DB_HOST = "sql206.infinityfree.com";
    const DB_NAME = "if0_39291681_botica";
    const DB_USER = "if0_39291681";
    const DB_PASSWORD = "GXzrIt5MtEG9";
    const SGBD = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
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
