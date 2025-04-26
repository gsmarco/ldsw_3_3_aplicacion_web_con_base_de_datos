<?php
// Parametros de conexión a la base de datos
$host = 'localhost';
$dbname = 'catalogo';
$username = 'root';
$password = 'Olga0322';

// Conexión a la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$pdo->exec("set names utf8"); // Establecemos la codificación UTF8 de la base de datos 

// Procedimientos de acceso y manipulación de la tabla productos
$action = $_GET['action'] ?? '';
switch ($action) {
    case 'list':
        $stmt = $pdo->query("SELECT * FROM productos");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
        break;

    case 'create':
        try {
            $codigo = $_POST['codigo'];
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $categoria = $_POST['categoria'];

            $stmt = $pdo->prepare("INSERT INTO productos (codigo, nombre, precio, categoria) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([$codigo, $nombre, $precio, $categoria]);

            if ($result) {
                $id = $pdo->lastInsertId();
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'id' => $id]);
            } else {
                throw new Exception("Error al insertar");
            }
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'update':
        $id = $_POST['id'];
        $codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $categoria = $_POST['categoria'];

        $stmt = $pdo->prepare("UPDATE productos SET codigo=?, nombre = ?, precio = ?, categoria = ? WHERE id = ?");
        $result = $stmt->execute([$codigo, $nombre, $precio, $categoria, $id]);

        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
        break;

    case 'delete':
        $id = $_GET['id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
            $result = $stmt->execute([$id]);
            header('Content-Type: application/json');
            echo json_encode(['success' => $result]);
        } catch (error) {
            echo json_encode(['error' => false]);
        }
        break;

    case 'get':
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($user);
        break;    
}
