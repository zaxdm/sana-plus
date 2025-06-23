<?php 
	
	//Datos de conexion a base de datos
	const DB_HOST = "localhost";
    const DB_NAME = "botica";
    const DB_USER = "root";
	const DB_PASSWORD = "";
	const SGBD="mysql:host=".DB_HOST.";dbname=".DB_NAME;

	// Datos de conexión a base de datos en InfinityFree
    // const DB_HOST = "sql206.infinityfree.com";
    // const DB_NAME = "if0_39291681_botica";
    // const DB_USER = "if0_39291681";
    // const DB_PASSWORD = "GXzrIt5MtEG9";
    // const SGBD = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;

	//Datos para encryptacion
	const METHOD="AES-256-CBC";
    const SECRET_KEY='$#*ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890#*';
	const SECRET_IV='20001109108103975194753';
	 
