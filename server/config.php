<?php 


/*======================================================================
// Archivo con los parametros de Configuracion de servidor
*/
$ipServer = gethostbyname(gethostname()); 
$config = array("websocket"=>  array() );

$config["websocket"]= array(
 'hostaname' =>  gethostname(), 
 'ip' => $ipServer, 
 'port'=> "9300", 
 'hash' => 'F736E021-AAE6-FFBD-CEBE-A64294FC34B1', 
 'bk_port' => 8079
); 

$config["websocket"]["server"] = "http://". $config["websocket"]["ip"]. ":{$config["websocket"]['bk_port']}/GestionVista/Contenido/httpQuitsObtenerPrograma?sckt_hash=". $config["websocket"]["hash"]; 

$config["database"]= array(
 'host' => "localhost:3306", // $config["websocket"]["ip"] . ":3306", 
 'user' => "root", 
 "password" => "", //"Bis123456", 
 'port'=> "3306", 
 "db"=> "bis_gestionvista"
);

?>