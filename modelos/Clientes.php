<?php 
//Incluimos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Clientes
{
	// Constructor vacío
	public function __construct()
	{

	}

	// Método para insertar registros
	public function insertar($cedula, $nombre, $direccion, $telefono,
							  $nombre_referencia1, $telefono_referencia1, $direccion_referencia1,
							  $nombre_referencia2, $telefono_referencia2, $direccion_referencia2)
	{
		$sql = "INSERT INTO clientes 
		(cedula, nombre, direccion, telefono, 
		nombre_referencia1, telefono_referencia1, direccion_referencia1,
		nombre_referencia2, telefono_referencia2, direccion_referencia2,
		estado)
		VALUES (
		'$cedula', '$nombre', '$direccion', '$telefono',
		'$nombre_referencia1', '$telefono_referencia1', '$direccion_referencia1',
		'$nombre_referencia2', '$telefono_referencia2', '$direccion_referencia2',
		'1')";
		
		return ejecutarConsulta($sql);
	}
    
	// Método para editar registros
	public function editar($idcliente, $cedula, $nombre, $direccion, $telefono,
						   $nombre_referencia1, $telefono_referencia1, $direccion_referencia1,
						   $nombre_referencia2, $telefono_referencia2, $direccion_referencia2)
	{
		$sql = "UPDATE clientes SET 
					cedula = '$cedula',
					nombre = '$nombre',
					direccion = '$direccion',
					telefono = '$telefono',
					nombre_referencia1 = '$nombre_referencia1',
					telefono_referencia1 = '$telefono_referencia1',
					direccion_referencia1 = '$direccion_referencia1',
					nombre_referencia2 = '$nombre_referencia2',
					telefono_referencia2 = '$telefono_referencia2',
					direccion_referencia2 = '$direccion_referencia2'
				WHERE idcliente = '$idcliente'";
		
		return ejecutarConsulta($sql);
	}

	// Método para desactivar un cliente
	public function desactivar($idcliente)
	{
		$sql = "UPDATE clientes SET estado = '0' WHERE idcliente = '$idcliente'";
		return ejecutarConsulta($sql);
	}

	// Método para activar un cliente
	public function activar($idcliente)
	{
		$sql = "UPDATE clientes SET estado = '1' WHERE idcliente = '$idcliente'";
		return ejecutarConsulta($sql);
	}

	// Método para mostrar los datos de un registro específico
	public function mostrar($idcliente)
	{
		$sql = "SELECT * FROM clientes WHERE idcliente = '$idcliente'";
		return ejecutarConsultaSimpleFila($sql);
	}

	// Método para listar todos los registros
	public function listar()
	{
		$sql = "SELECT * FROM clientes";
		return ejecutarConsulta($sql);		
	}

	// Método para listar clientes activos para el select
	public function select()
	{
		$sql = "SELECT idcliente, nombre FROM clientes WHERE estado = 1 ORDER BY nombre ASC";
		return ejecutarConsulta($sql);		
	}
}
?>
