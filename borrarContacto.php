<?php
require_once 'funciones.php';
$idContacto = $_GET['data'];

print $idContacto;

$con = conexionDB();

// $sql = 'DELETE FROM tutorials_tbl WHERE tutorial_id = 3';
$consulta = "DELETE from contacto where idcontacto=$idContacto";

$resultado = $con->query($consulta);

if($resultado){
	print "Dato borrado";
}

print '<a href="../../agenda/">Inicio</a>';



?>