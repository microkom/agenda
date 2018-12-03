<?php

/*
 * Haciendo uso de la clase Contacto, crea una clase llamada Agenda que contenga un array estático de objetos Contacto.
 * Crea un método para añadir un contacto a la Agenda, otro para un contacto y uno que convierta a cadena todo el objeto Agenda, 
 * de manera que en HTML se muestre un contacto de la agenda en cada línea (tendrás que poner <br> para los saltos de línea). 
 * Guarda esta clase en un fichero llamado agenda.inc.php.
 * 
 * Finalmente, crea una página php llamada t3-act1.php que cree un objeto Agenda, añada tres contactos a la misma y la muestre en la página. Después, eliminará el primer contacto y volverá a mostrar la agenda en la página.
 */

require_once('Contacto.inc.php');

class Agenda {

    private static $contactos = array();

    public function addContacto($contacto) {
        self::$contactos[] = $contacto;
    }

    public function readContacto($index) {
        return self::$contactos[$index]->__toString();
    }
    
	//Función que borra un contacto usando un índice
    public function delContacto($index) {
        unset(self::$contactos[$index]);
    }
    
    public function __toString() {
		$allText ="";
        foreach(self::$contactos as  $value){
          $allText .= $value->__toString();  
        }
		return $allText;
    }

}

?>
