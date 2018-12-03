<?php
function conexionDB(){
	$con  = mysqli_connect('localhost', 'root', '', 'agenda');

	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	return $con;

}

?>