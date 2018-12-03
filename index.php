<!DOCTYPE html>


<?php
session_start();

//variable de control
$modificar = false;

//definición a VACIO de las variables de error
function errors(){
	$errorNombre = $errorApellido1 = $errorUsuario = $errorTelefono = null;
}

require 'funciones.php';

//llamada a las variables de error
errors();

//inicio del contador de errores
$errorCounter = 0;


//Comprueba si se han enviado datos 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	//Comprueba si se ha presionado el boton Restablecer
	if (isset($_REQUEST['clear'])) {
		$errorCounter = 1;//al limpiar el formulario debe seguir visible
		errors();
		$nombre = $apellido1 = $usuario = $usuario = $pass1 = $telefono = "";


	}else{

		//Comprueba si el nombre fue escrito
		if (empty(trim($_POST['nombre']))) {
			$errorNombre = "* Escribe el nombre";
			$errorCounter++;
		} else {
			$nombre = trim($_POST['nombre']);
			if (!preg_match("/^[a-zA-Z ]*$/", $nombre)) {
				$errorNombre = "* Solo letras";
				$errorCounter++;
			}
		}

		if (empty(trim($_POST['apellido1']))) {
			$errorApellido1 = "* Escribe el Apellido";
			$errorCounter++;
		} else {
			$apellido1 = $_POST['apellido1'];
			if (!preg_match("/^[a-zA-Z ]*$/", $apellido1)) {
				$errorApellido1 = "* Sólo letras";
				$errorCounter++;
			}
		}

		if (empty(trim($_POST['usuario']))) {
			$errorUsuario = "* Escribe el usuario";
			$errorCounter++;
		} else {
			$usuario = $_POST['usuario'];
			if (!preg_match("/^[a-zA-Z ]*$/", $usuario)) {
				$errorUsuario = "* Sólo letras";
				$errorCounter++;
			}
		}

		if (empty(trim($_POST['telefono']))) {
			$errorTelefono = "* Escribe el número";
			$errorCounter++;
		} else {
			$telefono = $_POST['telefono'];
			if (!preg_match("/^[0-9 ]*$/", $telefono)) {
				$errorTelefono = "* Sólo números, no más de 9";
				$errorCounter++;
			}
		}



	}
}


/*BORRAR UN CONTACTO*/
if(isset($_GET['borrar'])){
	$idContactoBorrar = $_GET['borrar'];


	$con = conexionDB();

	$borrar = "DELETE from contacto where idcontacto=$idContactoBorrar";

	$resultadoBorrar = $con->query($borrar);

	if($resultadoBorrar){
		print "$idContactoBorrar borrado";
	}
	mysqli_close($con);

}    
/*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-**-*-**/


/*LEER DATOS PARA MODIFICAR UN CONTACTO*/
if(isset($_GET['modificar'])){
	$modificar = true;
	$con = conexionDB(); //conecta a la base de datos

	$idContacto = $_GET['modificar'];
	$_SESSION['id']= $idContacto;

	$resultado = $con->query("Select * from contacto where idContacto=".$idContacto."");
	if($resultado->num_rows == 0)
		echo 'La consulta no ha devuelto resultados.';


	//Leer todos los contactos de la base de datos
	$consulta = "Select * from contacto where idContacto=".$idContacto."";
	$resultado = $con->query($consulta);
	$stock = $resultado->fetch_object();

	$idContacto;
	$nombre = $stock->nombre;
	$apellido1 = $stock->apellido1;
	$usuario=  $stock->usuario;
	$telefono = $stock->telefono;

	mysqli_close($con);
}
/*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-**-*-**/


/*ACTUALIZAR REGISTROS*/
if(isset($_POST['modificar'])){

	$idContacto = $_SESSION['id'];
	$con = conexionDB();

	$consulta = $con->stmt_init();
	$consulta->prepare('UPDATE contacto SET nombre=?, apellido1=?,usuario=?,telefono=? WHERE idContacto=?;');
	$consulta->bind_param('sssii', $nombre,$apellido1,$usuario,$telefono,$idContacto);
	$consulta->execute();
	$consulta->close();

	mysqli_close($con);

	/*Limpiar formulario*/
	$nombre = $apellido1 = $usuario = $telefono= "";

}

/*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-**-*-**/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if ($errorCounter==0 && (isset($_POST['envio']))){
		require_once('Agenda.inc.php');

		$con = conexionDB(); //conecta a la base de datos

		//averiguar último contacto
		$contactoLast = 'SELECT MAX(idContacto) as idContacto from contacto';

		$resultadoLast = $con->query($contactoLast);
		$stockL = $resultadoLast->fetch_object();


		//ultimo id contacto de la base de datos
		$idContacto = $stockL->idContacto;

		$idContacto += 1; //próximo número de id de contacto
		$idAgenda = 1;
		
		print $usuario;
		if($modificar==false){

			$consulta = $con->stmt_init();
			$consulta->prepare("INSERT into contacto (idAgenda, idContacto, nombre, apellido1, usuario, telefono) VALUES (?,?,?,?,?,?)");
			$consulta->bind_param('iisssi', $idAgenda,$idContacto,$nombre,$apellido1,$usuario,$telefono);
			$consulta->execute();
			$consulta->close();


			//*********************************************

			
			//crea una orden para meter los datos en la base de datos 
	/*		
	$consulta = "INSERT into contacto (idAgenda, idContacto, nombre, apellido1, usuario, telefono) VALUES 					           ('1','$idContacto','$nombre','$apellido1', '$usuario', '$telefono')";


	//Ejecuta la inserción de datos desde el formulario
	$resultado = $con->query($consulta);
	*/	
		}
		//cerrar la conexión a la base de datos
		mysqli_close($con);
	}
}
?>



<html>
	<head>
		<meta charset="UTF-8">
		<title>Agenda</title>
		<style>
			* {
				font-family: sans-serif;
			}
			<?php
			if($errorCounter>0){
				print ".error{color:red;}";
			}

			?>
			td{
				padding: 3px 5px;
			}
		</style>
	</head>
	<body>


		<div>
			<h1>FORMULARIO DE REGISTRO</h1>

			<form action="<?php print $_SERVER["PHP_SELF"]; ?>" method="post">
				<fieldset>
					<legend>Ejercicio de PHP</legend>
					<table border="0" cellpadding="6" cellspacing="0">
						<tr>
							<td>Nombre</td>
							<td>
								<input type="text" name="nombre" size="35" value="<?php if(isset($nombre)) echo $nombre; ?>">
								<span class="error"><?php if(isset($errorNombre)) echo "$errorNombre";?></span> 
							</td>
						</tr>

						<tr>
							<td>Apellidos</td>
							<td>
								<input type="text" name="apellido1" size="35" value="<?php if(isset($apellido1)) echo $apellido1; ?>">	
								<span class="error"><?php  if(isset($errorApellido1))echo "$errorApellido1";?></span> 
							</td>
						</tr>
						<tr>
							<td>Usuario</td>
							<td>
								<input type="text" name="usuario" size="35" value="<?php if(isset($usuario)) echo $usuario; ?>">
								<span class="error"><?php  if(isset($errorUsuario)) echo "$errorUsuario";?></span> 



							</td>
						</tr>
						<tr>
							<td>Telefono</td>
							<td>
								<input type="number" name="telefono" size="35" value="<?php if(isset($telefono)) echo $telefono; ?>">
								<span class="error"><?php  if(isset($errorTelefono)) echo "$errorTelefono";?></span>
							</td>
						</tr>

						<tr><td colspan="2">
							<div class="center"><input type="submit" 
																<?php if($modificar == true){ 
	print 'name="modificar" value="Modificar"';
}else{
	print 'name="envio" value="Enviar"';} ?>></div></td></tr>
					</table>
				</fieldset>

			</form>

		</div>

		<?php

		/*MUESTRA LOS CONTACTOS DE LA BASE DE DATOS DEBAJO DEL FORMULARIO*/
		$con = conexionDB(); //conecta a la base de datos

		$resultado = $con->query('Select * from contacto');
		if($resultado->num_rows == 0)
			echo 'La consulta no ha devuelto resultados.';

		print "<h1>CONTACTOS DE LA AGENDA</h1>";

		//Leer todos los contactos de la base de datos
		$consulta = 'SELECT * from contacto';

		$resultado = $con->query($consulta);

		$stock = $resultado->fetch_object();
		print " <table><tr> ";

		while ($stock != null) {
			$id= $stock->idContacto;
			print $id;
			print "
            <td>$stock->nombre </td>
            <td>  $stock->apellido1 </td>
            <td>  $stock->usuario </td>
            <td> $stock->telefono </td>
            <td><a href=\"".$_SERVER["PHP_SELF"]."?borrar=$id\"><img src=\"bin.png\" height=\"20\" alt=\"Borrar contacto\"></a></td>
            <td><a href=\"".$_SERVER["PHP_SELF"]."?modificar=$id\"><img src=\"edit.png\" height=\"20\" alt=\"Modificar contacto\"></a></td>

            </tr>";

			$stock = $resultado->fetch_object();
		}
		print "</table>";

		mysqli_close($con);

		?>

	</body>
</html>
