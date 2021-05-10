<?php

// Datos de conexion
$motor="mysql"; //motor de base datos
$db = "paises_db"; // nombre de base de datos
$host = "127.0.0.1"; // localhost
$usuario="malcom";
$password="";

// Bloque try/catch
try {
    // pdo: conexión entre PHP y un servidor de bases de datos.
    $pdo= new PDO("$motor:dbname=$db;host=$host", $usuario, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));
    echo "Conectado";
}
//  PDOException: representa un error generado por PDO
catch(PDOExeption $e){
    echo "Error en la conexion" .$e->getMessage();
}
?>
