<?php 
/* wsRun
*/
set_time_limit(0);
date_default_timezone_set("America/Santo_Domingo"); 
// include the web sockets server script (the server is started at the far bottom of this file)
require 'config.php';
require 'libs/class.PHPWebSocket.php';
require 'libs/class.AdminBisTV.php'; 
require 'libs/class.Application.php';
require 'conexion/Conexion.php';
require 'conexion/instanciaDB.php';
require 'libs/helpers.php';



 $apps = Application::getInstance();
 $apps->Run(); 



 exit(); 



 $isRuning = true;

while ($isRuning) {

	$bd= ConexionDB::getInstance($config["database"]);
	$BisGestion = new AdminBisTV($bd);	

echo " ========== Programa de Socket para Red Smart TV. ================== \n "; 
try {

// Condicion Sigleton para el puerto. 

$Server = new PHPWebSocket();
$Server->bind('message', 'wsOnMessage');
$Server->bind('open', 'wsOnOpen');
$Server->bind('close', 'wsOnClose');
 
$Server->wsStartServer($config["websocket"]["ip"], $config["websocket"]["port"]);

	} catch (Exception $e) {

	    echo 'Exception: - ',  $e->getMessage(), " \n ";
	    $isRuning = true;
	}
}

echo " \n *******La Ejecucion ha finalizado*******  \n "; 



/*

stream_set_blocking(STDIN, 0);
echo "\n Favor Escriba el Comando: "; 
$csv_ar = fgetcsv(STDIN);
if (is_array($csv_ar)){
  print "CVS on STDIN\n";

} else {
  print "Look to ARGV for CSV file name.\n";
}


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