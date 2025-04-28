var tabla;

// Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });
}

// Función para limpiar los campos
function limpiar() {
    $("#idcliente").val("");
    $("#cedula").val("");
    $("#nombre").val("");
    $("#direccion").val("");
    $("#telefono").val("");
    $("#nombre_referencia1").val("");
    $("#telefono_referencia1").val("");
    $("#direccion_referencia1").val("");
    $("#nombre_referencia2").val("");
    $("#telefono_referencia2").val("");
    $("#direccion_referencia2").val("");
}

// Función para mostrar el formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

// Función para cancelar el formulario
function cancelarform() {
    limpiar();
    mostrarform(false);
}

// Función para listar los registros
function listar() {
    tabla = $('#tbllistado').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] // Exporta todas las columnas útiles
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            }
        ],
        "ajax": {
            url: '../ajax/clientes.php?op=listar',
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[1, "asc"]],
        "columnDefs": [
            { "targets": [6, 7, 8, 9, 10, 11], "visible": false } // Esconde las referencias en la tabla, pero sí se exportan
        ]
    });
}

// Función para guardar o editar
function guardaryeditar(e) {
    e.preventDefault();
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/clientes.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            bootbox.alert(datos);
            mostrarform(false);
            tabla.ajax.reload();
        }
    });
    limpiar();
}

// Función para mostrar un cliente
function mostrar(idcliente) {
    $.post("../ajax/clientes.php?op=mostrar", { idcliente: idcliente }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#idcliente").val(data.idcliente);
        $("#cedula").val(data.cedula);
        $("#nombre").val(data.nombre);
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#nombre_referencia1").val(data.nombre_referencia1);
        $("#telefono_referencia1").val(data.telefono_referencia1);
        $("#direccion_referencia1").val(data.direccion_referencia1);
        $("#nombre_referencia2").val(data.nombre_referencia2);
        $("#telefono_referencia2").val(data.telefono_referencia2);
        $("#direccion_referencia2").val(data.direccion_referencia2);
    });
}

// Función para desactivar un cliente
function desactivar(idcliente) {
    bootbox.confirm("¿Está seguro de desactivar el cliente?", function (result) {
        if (result) {
            $.post("../ajax/clientes.php?op=desactivar", { idcliente: idcliente }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

// Función para activar un cliente
function activar(idcliente) {
    bootbox.confirm("¿Está seguro de activar el cliente?", function (result) {
        if (result) {
            $.post("../ajax/clientes.php?op=activar", { idcliente: idcliente }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

init();
