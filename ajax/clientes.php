<?php 
require_once "../modelos/Clientes.php";

$cliente = new Clientes();

// Recibir datos del formulario
$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$cedula = isset($_POST["cedula"]) ? limpiarCadena($_POST["cedula"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";

// Nuevos campos de referencias
$nombre_referencia1 = isset($_POST["nombre_referencia1"]) ? limpiarCadena($_POST["nombre_referencia1"]) : "";
$telefono_referencia1 = isset($_POST["telefono_referencia1"]) ? limpiarCadena($_POST["telefono_referencia1"]) : "";
$direccion_referencia1 = isset($_POST["direccion_referencia1"]) ? limpiarCadena($_POST["direccion_referencia1"]) : "";

$nombre_referencia2 = isset($_POST["nombre_referencia2"]) ? limpiarCadena($_POST["nombre_referencia2"]) : "";
$telefono_referencia2 = isset($_POST["telefono_referencia2"]) ? limpiarCadena($_POST["telefono_referencia2"]) : "";
$direccion_referencia2 = isset($_POST["direccion_referencia2"]) ? limpiarCadena($_POST["direccion_referencia2"]) : "";

switch ($_GET["op"]) {
        
    case 'guardaryeditar':
        if (empty($idcliente)) {
            $rspta = $cliente->insertar($cedula, $nombre, $direccion, $telefono, 
                                        $nombre_referencia1, $telefono_referencia1, $direccion_referencia1, 
                                        $nombre_referencia2, $telefono_referencia2, $direccion_referencia2);
            echo $rspta ? "Cliente registrado" : "Cliente no se pudo registrar";
        } else {
            $rspta = $cliente->editar($idcliente, $cedula, $nombre, $direccion, $telefono, 
                                        $nombre_referencia1, $telefono_referencia1, $direccion_referencia1, 
                                        $nombre_referencia2, $telefono_referencia2, $direccion_referencia2);
            echo $rspta ? "Cliente actualizado" : "Cliente no se pudo actualizar";
        }
    break;

    case 'desactivar':
        $rspta = $cliente->desactivar($idcliente);
        echo $rspta ? "Cliente desactivado" : "Cliente no se pudo desactivar";
    break;

    case 'activar':
        $rspta = $cliente->activar($idcliente);
        echo $rspta ? "Cliente activado" : "Cliente no se pudo activar";
    break;

    case 'mostrar':
        $rspta = $cliente->mostrar($idcliente);
        echo json_encode($rspta);
    break;

    case 'listar':
        $rspta = $cliente->listar();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->estado) ?
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idcliente.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idcliente.')"><i class="fa fa-close"></i></button>'
                    :
                    '<button class="btn btn-warning" onclick="mostrar('.$reg->idcliente.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-primary" onclick="activar('.$reg->idcliente.')"><i class="fa fa-check"></i></button>',
                "1" => $reg->cedula,
                "2" => $reg->nombre,
                "3" => $reg->direccion,
                "4" => $reg->telefono,
                "5" => ($reg->estado) ? '<span class="label bg-primary">Activado</span>' : '<span class="label bg-warning">Desactivado</span>'
            );
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        echo json_encode($results);
    break;
}
?>
