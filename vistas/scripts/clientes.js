var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });
}

//Función para limpiar los campos
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

//Mostrar Formulario
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

//Función para cancelar formulario
function cancelarform() {
    limpiar();
    mostrarform(false);
}

//Función para listar registros
function listar() {
    tabla = $('#tbllistado').dataTable({
        "aProcessing": true, // Activamos el procesamiento del datatables
        "aServerSide": true, // Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', // Definimos los elementos del control de tabla
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'pdf'
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
        "iDisplayLength": 10, // Paginación
        "order": [
            [2, "asc"]
        ] // Ordenar (columna, orden)
    }).DataTable();
}

//Función para guardar o editar
function guardaryeditar(e) {
    e.preventDefault(); // No se activará la acción predeterminada del evento
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

//Función para mostrar un cliente en el formulario
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

//Función para desactivar un cliente
function desactivar(idcliente) {
    bootbox.confirm("¿Está Seguro de desactivar el Cliente?", function (result) {
        if (result) {
            $.post("../ajax/clientes.php?op=desactivar", { idcliente: idcliente }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

//Función para activar un cliente
function activar(idcliente) {
    bootbox.confirm("¿Está Seguro de activar el Cliente?", function (result) {
        if (result) {
            $.post("../ajax/clientes.php?op=activar", { idcliente: idcliente }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

init();
