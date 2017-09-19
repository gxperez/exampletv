<?php 
/* wsRun
Programa principal.
*/
set_time_limit(0);
date_default_timezone_set("America/Santo_Domingo"); 

// include the web sockets server script (the server is started at the far bottom of this file)
require 'class.PHPWebSocket.php';
require 'config.php';
require 'class.AdminBisTV.php'; 

require 'conexion/Conexion.php';
require 'conexion/instanciaDB.php';

$bd= ConexionDB::getInstance($config["database"]);

//$BisGestion = new AdminBisTV($bd); 











/*

require "conexion/tvAccion.php";
require 'conexion/Conexion.php';
require 'conexion/instanciaDB.php';

$bd= ConexionDB::getInstance();
$BisGestion = new AdminBisTV($bd); 


$Server = new PHPWebSocket();
$Server->bind('message', 'wsOnMessage');
$Server->bind('open', 'wsOnOpen');
$Server->bind('close', 'wsOnClose');

// for other computers to connect, you will probably need to change this to your LAN IP or external IP,
// alternatively use: gethostbyaddr(gethostbyname($_SERVER['SERVER_NAME']))

  $Server->wsStartServer('10.234.51.99', 9300); // ws://10.234.130.55:9300'  127.0.0.1 // 10.234.133.76

 // $Server->wsStartServer('10.234.133.76', 9300); // ws://10.234.130.55:9300'  127.0.0.1

 echo "corriendo 10.234.51.99:9300";

 */

?>