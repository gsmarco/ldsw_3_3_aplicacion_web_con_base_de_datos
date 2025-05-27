<?php

// Conectar a la base de datos
//   Parámetros de conexión
$host = 'localhost';
$db   = 'catalogo';
$end_point="";
$user = 'root';
$pass = 'Olga0322';
$port = '3306';
  
$host = 'us-east-2.aws.neon.tech';
$db   = 'neondb';
$end_point="options=endpoint=ep-little-boat-a5s7r8sd-pooler";
$user = 'neondb_owner';
$pass = 'npg_kj5t0RPngJZF';
$port = '5432';
$sslmode = 'require';

$dsn = "pgsql:host=$host;port=$port;dbname=$db $end_point;sslmode=$sslmode;";
?>
