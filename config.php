<?php
// $host = 'mysql-asans.alwaysdata.net';
// $dbname = 'asans_grupo_guarani';
// $username = 'asans';
// $password = 'soydaw2025';

// Esto es DEV
// $host = '127.0.0.1';
// $dbname = 'grupo_guarani_local';
// $username = 'root';
// $password = '';


// Esto es PROD
 $host = 'mysql-sulaiman.alwaysdata.net';
 $dbname = 'sulaiman__';
 $username = 'sulaiman_';
 $password = 'APTItude01';




$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}


$mysqli->set_charset("utf8mb4");
