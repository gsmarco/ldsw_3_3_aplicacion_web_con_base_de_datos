<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Si ya existe una sesión, redirigir a la página productos.php
if (isset($_SESSION['usuario_email'])) {
  header('Location: productos.php');
  exit();
}

// Verificamos si se esta utilizando el método REQUEST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Conectar a la base de datos
  // Parámetros de conexión
  $host = 'localhost';
  $db   = 'catalogo';
  $user = 'gsmarco';
  $pass = 'Olga0322';
  $port = '5432';

  try {
    // Crear conexión PDO
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Obtener datos del formulario: email y password
    $email = $_POST['email'] ?? '';
    $inputPassword = $_POST['password'] ?? '';

    // Modificar la consulta para obtener todos los datos necesarios del usuario
    $stmt = $pdo->prepare("SELECT id, email, password, nombre FROM usuarios WHERE email = :email");

    // Ejecutar consulta con parámetro
    $stmt->execute(['email' => $email]);

    // Obtener un solo registro
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $password = $row['password'];
        // Verificar la contraseña obtenida de la base d datos contra la recibida mediante POST
        if ($inputPassword === $password) {
          $_SESSION['usuario_email'] = $email;
          header('Location: productos.php'); // Redirecciona al usuario a la página productos.php
          exit();
        } else {
          $error = "Contraseña incorrecta"; // Error en la contraseña
        }
      } else {
        echo "Usuario no encontrado.";
    }

    // se cierra la conexión
    $stmt->close();
    $conn->close();
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Form</title>
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"
    rel="stylesheet" />

</head>

<body class="bg-light">
  <div class="container">
    <div class="row vh-100 align-items-center justify-content-center">
      <div class="col-12 col-sm-8 col-md-6 col-lg-4">
        <div class="card shadow">
          <div class="card-body p-5">
            <h2 class="text-center mb-4">Iniciar Sesión</h2>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  name="email"
                  placeholder="nombre@ejemplo.com"
                  required />
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  name="password"
                  placeholder="Ingresa tu contraseña"
                  required />
              </div>
              <button type="submit" class="btn btn-primary w-100">
                Ingresar
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js">
  </script>

</body>

</html>