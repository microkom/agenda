<?php
/*
Crea una clase llamada Contacto que contenga los siguientes campos: idContacto, nombre, apellido1, apellido2 y teléfono.

Implementar el constructor, los getters, los setters y el método de conversión a cadena correspondientes. Guarda esta clase en un fichero llamado contacto.inc.php.

*/

class Contacto{

	private $idContacto;
	private $nombre;
	private $apellido1;
	private $apellido2;
	private $telefono;

	public function __construct($idContacto, $nombre, $apellido1, $apellido2, $telefono){
		$this->	idContacto = $idContacto;
		$this->	nombre = $nombre;
		$this-> apellido1 = $apellido1;
		$this-> apellido2 = $apellido2;
		$this-> telefono = $telefono;
	}
			
	public function __get(mixed $name){
	}
	public function __set(string $name, mixed $value){
	}


	public function __toString() {
		return 'IdContacto: '.$this->idContacto.
			'<br>Nombre: '.$this->nombre.
			'<br>Apellido-1: '.$this->apellido1.
			'<br>Apellido-2: '.$this->apellido2.
			'<br>Teléfono: '.$this->telefono.
			'<br><br>';
	}
}
/*
Comentario desde github}

/*
Comentario de prueba de github
*/
?>
