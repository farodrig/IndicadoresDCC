<?php
// La funcion debe imprimir una URL donde ADI dirigira al usuario en caso de exito o '-1' en caso de error


ini_set( "session.use_cookies",  0 );
ini_set( "magic_quotes_runtime", 0 );
ini_set( "magic_quotes_gpc",	 0 );

$firma = base64_decode( $_POST['firma'] );
unset( $_POST['firma'] );

$public_key = openssl_pkey_get_public( file_get_contents( 'https://www.u-cursos.cl/upasaporte/certificado' ) );
$result     = openssl_verify( array_reduce( $_POST, create_function( '$a,$b', 'return $a.$b;' ) ), $firma, $public_key );

openssl_free_key( $public_key );

// Si el resultado es negativo significa que el mensaje no est� siendo enviado por U-Pasaporte y por lo tanto debemos retornar un error.
if( ! $result ) exit( '-1' );


// Si el script llega a este punto, significa que
// ADI valido al usuario con exito y envio la informacion de este a traves del arreglo $_POST.
// Este script por su parte debe validar al usuario (por ejemplo, que cumpla un determinado perfil)
// e imprimir una URL donde ADI dirigira al usuario.
// Se recomienda crear una session en este punto y entregar el id en la URL que se imprime
// Ej:

session_start();
$_SESSION = array(
	'rut'				=> $_POST['rut'],
	'nombre_completo'	=> $_POST['nombre_completo'],
	'valido'			=> TRUE,
);

$paths = explode("/", $_SERVER['REQUEST_URI']);

exit( 'http://'.$_SERVER['SERVER_NAME'].'/'.$paths[1].'/verif_usuario?'.session_name().'='.session_id() );
