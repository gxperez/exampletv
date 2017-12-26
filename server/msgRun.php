<?php 
/* wsRun
*/
set_time_limit(0);
date_default_timezone_set("America/Santo_Domingo"); 
// include the web sockets server script (the server is started at the far bottom of this file)
require 'configvar.php';
require 'libs/class.PHPWebSocket.php';
require 'libs/class.AdminBisTV.php'; 
require 'libs/class.ListenServicesMensaje.php';
require 'conexion/Conexion.php';
require 'conexion/instanciaDB.php';
require 'libs/helpers.php';



 $apps = new ListenServicesMensaje(); // ::getInstance(); 
 // $apps->_extract = true; 
 $apps->run(); 



 exit(); 


?>