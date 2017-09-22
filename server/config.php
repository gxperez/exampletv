<?php 


/*======================================================================
// Archivo con los parametros de Configuracion de servidor de Web Socket
* Autor: Grequis Xavier Perez
*/

// Hos IP por defecto la IP del local server.
$ipServer = gethostbyname(gethostname()); 
$config = array("websocket"=>  array() );
$config["websocket"]= array(
 'hostaname' =>  gethostname(), 
 'ip' => $ipServer, 
 'port'=> "9300", 
 'hash' => 'F736E021-AAE6-FFBD-CEBE-A64294FC34B1', 
 'bk_port' => 8079
); 


/*// Servidor de Administracion de Contenido. donde esta alojado
	Ejemplo: La ip Local y el puerto del Backend de Gestion a la Vista.
	**********************************************************************
	$config["websocket"]["server_backend"] =
	 "http://". $config["websocket"]["ip"]. ":{$config["websocket"]['bk_port']}/GestionVista/Contenido/httpQuitsObtenerPrograma?sckt_hash=". $config["websocket"]["hash"];

	 $config["websocket"]["baseURL"] = "http://". $config["websocket"]["ip"]. ":{$config["websocket"]['bk_port']}/GestionVista/"; 
	 **********************************************************************

*/

$config["websocket"]["server_backend"] = "http://10.234.51.99:8079/GestionVista/Contenido/httpQuitsObtenerPrograma?sckt_hash=F736E021-AAE6-FFBD-CEBE-A64294FC34B1";

$config["websocket"]["baseURL"] =  "http://10.234.51.99:8079/GestionVista/"; 

/**
	Datos de Conexion a Base de Datos. La misma Base de datos del Backend Gestion a la vista.
	Ejemplo: Datos por defecto para el servidor
	***********************************************************************
	$config["database"]= array(
	 'host' => $config["websocket"]["ip"] . ":3306", 
	 'user' => "root", 
	 "password" => "Bis123456", 
	 'port'=> "3306", 
	 "db"=> "bis_gestionvista"
	);
	***********************************************************************
*/

$config["database"]= array(
 'host' => "localhost:3306", 
 'user' => "root", 
 "password" => "123", 
 'port'=> "3306", 
 "db"=> "bis_gestionvista"
);


/**
 Declaracion de Varibla Global.
 No se toca.
**/
$integracionConfig = array(
'server' => $config["websocket"]["server_backend"], 
"baseURL"=> $config["websocket"]["baseURL"]
);

$Server = false; 
$bd = false; 
$BisGestion = false; 
/**
***********************************************************************
*/
?>