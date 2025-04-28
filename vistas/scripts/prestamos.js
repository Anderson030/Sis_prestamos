var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });

    // Cargar Clientes
    $.post("../ajax/prestamos.php?op=selectCliente", function (r) {
        $("#idcliente").html(r);
        $('#idcliente').selectpicker('refresh');
    });

    // Cargar Usuarios
    $.post("../ajax/prestamos.php?op=selectUsuario", function (r) {
        $("#usuario").html(r);
        $('#usuario').selectpicker('refresh');
    });

    // Cargar fecha actual en fecha de préstamo
    var now = new Date();
    var today = now.toISOString().split('T')[0];
    $('#fprestamo').val(today);
}

function limpiar() {
    $("#idprestamo").val("");
    $("#idcliente").val("").selectpicker('refresh');
    $("#usuario").val("").selectpicker('refresh');
    $("#fprestamo").val(new Date().toISOString().split('T')[0]);
    $("#monto").val("").selectpicker('refresh');
    $("#interes").val("").selectpicker('refresh');
    $("#saldo").val("");
    $("#formapago").val("").selectpicker('refresh');
    $("#fechapago").val("");
    $("#plazo").val("").selectpicker('refresh');
    $("#fplazo").val("");
}

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

function cancelarform() {
    limpiar();
    mostrarform(false);
}

function listar() {
    tabla = $('#tbllistado').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5'],
        "ajax": {
            url: '../ajax/prestamos.php?op=listar',
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    });
}

function guardaryeditar(e) {
    e.preventDefault();
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/prestamos.php?op=guardaryeditar",
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

function mostrar(idprestamo) {
    $.post("../ajax/prestamos.php?op=mostrar", { idprestamo: idprestamo }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#idprestamo").val(data.idprestamo);
        $("#idcliente").val(data.idcliente).selectpicker('refresh');
        $("#usuario").val(data.usuario).selectpicker('refresh');
        $("#fprestamo").val(data.fecha);
        $("#monto").val(data.monto).selectpicker('refresh');
        $("#interes").val(data.interes).selectpicker('refresh');
        $("#saldo").val(data.saldo);
        $("#formapago").val(data.formapago).selectpicker('refresh');
        $("#fechapago").val(data.fechap);
        $("#plazo").val(data.plazo).selectpicker('refresh');
        $("#fplazo").val(data.fechaf);
    });
}

function eliminar(idprestamo) {
    bootbox.confirm("\u00bfEst\u00e1 seguro de eliminar el préstamo?", function (result) {
        if (result) {
            $.post("../ajax/prestamos.php?op=eliminar", { idprestamo: idprestamo }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

init();