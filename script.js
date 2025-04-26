// Inicializar DataTableñ
$(document).ready(function () {
  var table;
  // Inicializar DataTable
  var table = $("#productTable").DataTable({
    ajax: {
      url: "api.php?action=list",
      dataSrc: "",
    },
    columns: [
      { data: "id" },
      { data: "codigo" },
      { data: "nombre" },
      { data: "precio" },
      { data: "categoria" },
      {
        data: null,
        render: function (data, type, row) {
          return (
            '<button class="btn btn-warning btn-sm edit-btn" data-id="' +
            row.id +
            '">Editar</button>' +
            "&nbsp; &nbsp; &nbsp; " +
            '<button class="btn btn-danger btn-sm delete-btn" data-id="' +
            row.id +
            '">Eliminar</button>'
          );
        },
      },
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json",
    },
    order: [[1, "asc"]], // Ordenar por ID descendente

    // Hook para animación al cambiar página
    drawCallback: function (settings) {
      var pagination = $(this)
        .closest(".dataTables_wrapper")
        .find(".dataTables_paginate");

      // Animación de página actual
      this.api()
        .rows({ page: "current" })
        .nodes()
        .each(function (row) {
          $(row).addClass("pagination-animation");
        });
    },
  });

  // Funcion para agregar un producto
  function agregaProducto() {
    var id = $("#productId").val();

    var productData = {
      id: id,
      codigo: $("#codigo").val(),
      nombre: $("#nombre").val(),
      precio: $("#precio").val(),
      categoria: $("#categoria").val(),
    };

    if (!verificaCampos(productData)) {
      return;
    }
    productData.precio = editaNumero(productData.precio.trim());

    try {
      $.ajax({
        url: "api.php?action=create",
        method: "POST",
        data: productData,
        success: function (response) {
          if (response.success) {
            // Limpiar el formulario
            $("#productForm")[0].reset();

            // Cerrar el modal
            $("#modalForm").modal("hide");

            // Añadir el nuevo registro a la tabla con animación
            var newRow = table.row
              .add({
                id: response.id,
                codigo: productData.codigo,
                nombre: productData.nombre,
                precio: productData.precio,
                categoria: productData.categoria,
              })
              .draw(false)
              .node();
            // Añadir clase para animación
            $(newRow).addClass("new-row");

            // Resaltar la fila temporalmente
            $(newRow).addClass("highlight");
            setTimeout(function () {
              $(newRow).removeClass("highlight");
            }, 2000);

            Swal.fire("¡Éxito!", "Producto agregado correctamente.", "success");
          } else {
            Swal.fire("Error", "No se pudo agregar el producto.", "error");
          }
        },
        error: function () {
          Swal.fire("Error", "Ocurrió un error en el servidor.", "error");
        },
      });
    } catch (error) {
      alert(error);
    }
  }

  //************************************************************************/
  // Eliminar Producto
  // Manejador de eliminación
  $(document).on("click", ".delete-btn", function () {
    var btn = $(this);
    var id = btn.data("id");
    var tr = btn.closest("tr");
    Swal.fire({
      title: "¿Estás seguro de eliminar el registro " + id + "?",
      text: "No podrás revertir esta acción",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "api.php?action=delete&id=" + id,
          method: "POST",
          // data: {
          //     id: id
          // },
          success: function (response) {
            if (response.success) {
              // Añadir clase para animación de fadeOut
              tr.addClass("fade-out");

              // Esperar a que termine la animación antes de eliminar la fila
              setTimeout(function () {
                table.row(tr).remove().draw(false);

                Swal.fire(
                  "¡Eliminado!",
                  "El registro ha sido eliminado.",
                  "success"
                );
              }, 500);
            } else {
              Swal.fire("Error", "No se pudo eliminar el registro.", "error");
            }
          },
          error: function () {
            Swal.fire("Error", "Ocurrió un error en el servidor.", "error");
          },
        });
      }
    });
  });

  // Carga el modal de Editar producto
  $("#productTable").on("click", ".edit-btn", function () {
    var row = table.row($(this).closest("tr")).data();
    $("#productId").val(row.id);
    $("#codigo").val(row.codigo);
    $("#nombre").val(row.nombre);
    $("#precio").val(row.precio);
    $("#categoria").val(row.categoria);

    $("#modalForm").modal("show");
  });

  $("#cancelar").click(function () {
    $("#productForm")[0].reset();

    // Cerrar el modal
    $("#modalForm").modal("hide");
  });

  // Manejador para actualizar un producto
  $("#enviar").click(function () {
    productId = $("#productId").val();

    if (productId == "") {
      agregaProducto();
      return;
    }

    var formData = {
      id: productId,
      codigo: $("#codigo").val(),
      nombre: $("#nombre").val(),
      precio: $("#precio").val(),
      categoria: $("#categoria").val(),
    };

    if (!verificaCampos(formData)) {
      $("#productForm")[0].reset();
      return;
    }
    formData.precio = editaNumero(formData.precio.trim());

    $.ajax({
      url: "api.php?action=update",
      method: "POST",
      data: formData,
      success: function (response) {
        if (response.success) {
          // Limpiar el formulario
          $("#productForm")[0].reset();

          // Cerrar el modal
          $("#modalForm").modal("hide");

          // Actualizar la tabla
          var row = table.row(function (idx, data) {
            return data.id == formData.id;
          });

          // Aplicar animación mejorada al renglon actualizado
          var rowNode = row.node();
          $(rowNode).addClass("update-flash");

          // Efecto de vibración suave
          $(rowNode).css({
            animation: "none",
          });
          setTimeout(function () {
            $(rowNode).css({
              animation: "updateFlash 1.5s ease-in-out",
            });
          }, 1);

          // Actualizar datos
          setTimeout(function () {
            row
              .data({
                id: formData.id,
                codigo: formData.codigo,
                nombre: formData.nombre,
                precio: formData.precio,
                categoria: formData.categoria,
              })
              .draw(false);

            // Remover clases de animación
            setTimeout(function () {
              $(rowNode).removeClass("update-flash");
            }, 1500);
          }, 750);

          Swal.fire(
            "¡Actualizado!",
            "Producto actualizado correctamente.",
            "success"
          );
        } else {
          Swal.fire("Error", "No se pudo actualizar el producto.", "error");
        }
      },
      error: function () {
        Swal.fire("Error", "Ocurrió un error en el servidor.", "error");
      },
    });
  });

  function verificaCampos(productData) {
    try {
      let mensaje = "";
      if (productData.codigo.trim() == "") {
        mensaje = "Verifique el codigo del producto." + " ";
      }
      if (productData.nombre.trim() == "") {
        mensaje += "Verifique la descripción del producto." + " ";
      }

      if (!isNumber(productData.precio.trim())) {
        mensaje += "Verifique el precio del producto." + " ";
      }

      if (productData.categoria.trim() == "") {
        mensaje += "Seleccione la categoría del producto." + " ";
      }

      if (mensaje.trim() != "") {
        Swal.fire("Error", mensaje, "error");
        return false;
      }
    } catch (error) {
      alert(error);
      return false;
    }
    return true;
  }

  function isNumber(valor) {
    try {
      return !isNaN(parseFloat(valor)) && isFinite(valor);
    } catch (error) {
      return false;
    }
  }

  function editaNumero(valor) {
    let numero = Number(valor);
    return numero.toFixed(2);
  }
});
