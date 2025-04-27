<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validar si existe una sesión
if (!isset($_SESSION['usuario_email'])) {
  header('Location: login.php'); // No existe, redireccion al formulario de login
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DataTables CRUD Example</title>

  <!-- CSS -->
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"
    rel="stylesheet" />

  <link
    href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css"
    rel="stylesheet" />

  <!-- SweetAlert2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

  <link href="style.css" rel="stylesheet" />
</head>

<body>
  <div class="container mt-4">
    <h2>Gestión de Productos</h2>

    <button
      type="button"
      class="btn btn-primary mb-3"
      data-bs-toggle="modal"
      data-bs-target="#modalForm">
      Agregar Nuevo Producto
    </button>

    <!-- Tabla -->
    <table id="productTable" class="table table-striped MiTabla">
      <thead>
        <tr>
          <th>Id</th>
          <th>Codigo</th>
          <th>Descripción</th>
          <th>Precio</th>
          <th>Categoría</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <!-- Los datos se cargarán dinámicamente -->
      </tbody>
    </table>
  </div>

  <!-- Modal Form -->
  <div class="modal fade" id="modalForm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Agregando/editando Producto</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="productForm">
            <input type="hidden" id="productId" name="productId" />

            <div class="mb-3">
              <label for="codigo" class="form-label">Codigo</label>
              <input
                type="text"
                class="form-control"
                id="codigo"
                name="codigo"
                required />
            </div>

            <div class="mb-3">
              <label for="nombre" class="form-label">Descripcion</label>
              <input
                type="text"
                class="form-control"
                id="nombre"
                name="nombre"
                required />
            </div>
            <div class="mb-3">
              <label for="precio" class="form-label">Precio</label>
              <input
                type="number"
                class="form-control"
                id="precio"
                name="precio"
                required />
            </div>
            <div class="mb-3">
              <label for="categoria" class="form-label">Categoría</label>
              <select
                class="form-control"
                id="categoria"
                name="categoria"
                required>
                <option value="">Seleccione una categoría</option>
                <option value="ACCESORIOS">Accesorios</option>
                <option value="CHAPAS">Chapas</option>
                <option value="CINTAS">Cintas</option>
                <option value="CUBETAS">Cubetas</option>
                <option value="ELECTRICIDAD">Electricidad</option>
                <option value="ELECTRONICA">Electronica</option>
                <option value="GAS">Gas</option>
                <option value="ILUMINACION">Iluminacion</option>
                <option value="LIJAS">Lijas</option>
                <option value="LIMPIEZA">Limpieza</option>
                <option value="NAVAJAS">Navajas</option>
                <option value="PEGAMENTOS">Pegamentos</option>
                <option value="TAQUETES">Taquetes</option>
                <option value="TORNILLERIA">Tornilleria</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button
            type="button" id="cancelar"
            class="btn btn-secondary"
            data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" id="enviar" class="btn btn-primary" data-bs-dismiss="modal">
            Guardar
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="trash-icon">
    <i class="fas fa-trash-alt"></i>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="script.js"></script>
</body>

</html>