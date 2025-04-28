<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
} else {
    require 'header.php';

    if ($_SESSION['Prestamos'] == 1) {
?>
<!-- Inicio Contenido -->
<div class="row">
    <div class="col-lg-12">
        <div class="main-box clearfix">
            <header class="main-box-header clearfix">
                <h2 class="box-title">Préstamos
                    <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)">
                        <i class="fa fa-plus-circle"></i> Nuevo
                    </button>
                </h2>
            </header>

            <div class="main-box-body clearfix" id="listadoregistros">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-hover" id="tbllistado">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Cliente</th>
                                <th>Prestamista</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Interés</th>
                                <th>Saldo</th>
                                <th>Forma de Pago</th>
                                <th>Fecha Primer Pago</th>
                                <th>Plazo</th>
                                <th>Fecha Final</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="main-box-body clearfix" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Cliente</label>
                            <input type="hidden" name="idprestamo" id="idprestamo">
                            <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true" required></select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Prestamista</label>
                            <select name="usuario" id="usuario" class="form-control selectpicker" data-live-search="true" required></select>
                            <input type="hidden" class="form-control" name="fprestamo" id="fprestamo" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Monto</label>
                            <select name="monto" id="monto" class="form-control selectpicker" required>
                                <option value="">Seleccione un monto</option>
                                <option value="100000">$100.000</option>
                                <option value="200000">$200.000</option>
                                <option value="300000">$300.000</option>
                                <option value="400000">$400.000</option>
                                <option value="500000">$500.000</option>
                                <option value="600000">$600.000</option>
                                <option value="700000">$700.000</option>
                                <option value="800000">$800.000</option>
                                <option value="900000">$900.000</option>
                                <option value="1000000">$1.000.000</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Interés</label>
                            <select name="interes" id="interes" class="form-control selectpicker" required>
                                <option value="20">20%</option>
                                <option value="15">15%</option>
                                <option value="13">13%</option>
                                <option value="10">10%</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Saldo</label>
                            <input type="text" name="saldo" id="saldo" class="form-control" placeholder="Saldo" readonly required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Forma de Pago</label>
                            <select name="formapago" id="formapago" class="form-control selectpicker" required>
                                <option value="Diario">Diario</option>
                                <option value="Semanal">Semanal</option>
                                <option value="Quincenal">Quincenal</option>
                                <option value="Mensual">Mensual</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Fecha Primer Pago</label>
                            <input type="date" name="fechapago" id="fechapago" class="form-control" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Plazo</label>
                            <select name="plazo" id="plazo" class="form-control selectpicker" required>
                                <option value="Dia">Día</option>
                                <option value="Semana">Semana</option>
                                <option value="Quincena">Quincena</option>
                                <option value="Mes">Mes</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Fecha Cancelación</label>
                            <input type="date" name="fplazo" id="fplazo" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group col-12 text-center mt-3">
                        <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                        <button class="btn btn-danger" type="button" onclick="cancelarform()"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
?>

<!-- SCRIPT personalizado para préstamos -->
<script>
$(document).ready(function () {
    var montoSeleccionado = 0;

    function inicializarFechas() {
        if ($('#formapago').val() != "") {
            calcularFechaPago();
        }
        if ($('#plazo').val() != "") {
            calcularFechaCancelacion();
        }
    }

    $('#monto').on('change', function () {
        montoSeleccionado = parseFloat($(this).val()) || 0;
        calcularSaldo();
    });

    $('#interes').on('change', function () {
        calcularSaldo();
    });

    function calcularSaldo() {
        var interes = parseFloat($('#interes').val()) || 0;
        var saldo = montoSeleccionado + (montoSeleccionado * interes / 100);
        $('#saldo').val(saldo.toLocaleString('es-CO', { style: 'currency', currency: 'COP' }));
    }

    function sumaDias(dias) {
        var fecha = new Date();
        fecha.setDate(fecha.getDate() + dias);
        return fecha.toISOString().split('T')[0];
    }

    function calcularFechaPago() {
        var valor = $('#formapago').val();
        if (valor === 'Diario') $('#fechapago').val(sumaDias(1));
        if (valor === 'Semanal') $('#fechapago').val(sumaDias(7));
        if (valor === 'Quincenal') $('#fechapago').val(sumaDias(15));
        if (valor === 'Mensual') $('#fechapago').val(sumaDias(30));
    }

    function calcularFechaCancelacion() {
        var valor = $('#plazo').val();
        if (valor === 'Dia') $('#fplazo').val(sumaDias(1));
        if (valor === 'Semana') $('#fplazo').val(sumaDias(7));
        if (valor === 'Quincena') $('#fplazo').val(sumaDias(15));
        if (valor === 'Mes') $('#fplazo').val(sumaDias(30));
    }

    $('#formapago').on('change', function () {
        calcularFechaPago();
    });

    $('#plazo').on('change', function () {
        calcularFechaCancelacion();
    });

    // Inicializar fechas automáticamente cuando se abre el formulario
    $("#btnagregar").on('click', function () {
        inicializarFechas();
    });
});
</script>

<script src="scripts/prestamos.js"></script>
<?php
}
ob_end_flush();
?>
