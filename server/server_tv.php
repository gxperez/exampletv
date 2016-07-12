<?php
// prevent the server from timing out
set_time_limit(0);
date_default_timezone_set("America/Santo_Domingo"); 
// include the web sockets server script (the server is started at the far bottom of this file)
require 'class.PHPWebSocket.php';
require 'class.AdminBisTV.php'; 


/* Varivable para solicitude via http. */

$glb_Hash = 'F736E021-AAE6-FFBD-CEBE-A64294FC34B1'; 	

// when a client sends data to the server
function wsOnMessage($clientID, $message, $messageLength, $binary) {

	global $Server;
	global $BisGestion; 

	$ip = long2ip( $Server->wsClients[$clientID][6] );
	$timeNow =  new DateTime(); 
	
	// $confRes = array( "accion"=> "ACTIVAR", "mensaje"=> "Esperando respuestas", "fecha"=> $timeNow->format('Y,m,d,H,i,s') ); 

	$Server->log("Recepcion de Mensajes.");
	$Server->log( $timeNow->format('Y,m,d,H,i,s')  );
	$Server->log("=================================");
	$Server->log($message); 

	
	if ($messageLength == 0) {
		$Server->wsClose($clientID);
		return;
	}

	$varible = json_decode($message);		


	if(array_key_exists("accion" , $varible ) ){

		if($varible->accion == "ACTIVAR"){
			// 
			$Server->wsClients[$clientID]; 
			$arrayName = array('Mac' => trim($varible->macAdrees),  'Ip'=> trim($ip));
			$Server->listTV[$clientID] = registrarValidarTV($arrayName);

			$Server->log("Cliente $clientID Esta conectado."); 						
			$retornos =	$BisGestion->setHasRefresh(); 
			$Server->log( print_r($retornos, true) ); 

		}

		return false; 
	}

	/// Ya entendí por que pasa Esto.

		foreach ( $Server->wsClients as $id => $client )

			if ( $id != $clientID ) {

			$varible = json_decode($message);
		

			if(array_key_exists("accion" , $varible ) ){


				$Server->log(print_r($varible, true));

				if($varible->accion == "ACTIVAR"){
					$arrayName = array('Mac' => trim($varible->macAdrees),  'Ip'=> trim($ip));					
// 					$Server->log(print_r($arrayName, true)); 					 
					 $Server->listTV[$clientID] = registrarValidarTV($arrayName); 
					 
					 $Server->log("Esta En linea el Cliente #: ". $clientID );					 
					 $Server->log($Server->listTV[$clientID]); 
					 $Server->log("====== Se Establecio la session con el servidor. ==========");

					 // Activacion consultar 
					 	$dataHoy = date("Y-m-d"); 					 	

					   if(array_key_exists("FechaPrograma" , $varible ) ){

					  //  	$varible["FechaPrograma"]

					   	// Validar que el Televisor envio 

					   } else {
					   	// Enviar la Actualizacion. 

					   }
				}
			}


			$confRes["mensaje"] = "Visitor $clientID ($ip) said \"$message\"" ;
				// $Server->wsSend($id, "Visitor $clientID ($ip) said \"$message\"" .  "=>: ". $varible);
				$Server->wsSend($id,  json_encode($confRes));				
		}
}

function cargarProgramacion(){


}

// when a client connects
function wsOnOpen($clientID)
{

	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );
	
	$tempDateNow =  new DateTime(); 
	$Server->log( "$ip  ($clientID) has connected." . json_encode($Server->wsClients[$clientID]));

	


	//Send a join notice to everyone but the person who joined
	foreach ( $Server->wsClients as $id => $client )		

		if ( $id != $clientID ) {
		$messal =  array(
						'mensaje' => '"Visitor $clientID ($ip) has joined the room. con el String"',
						'moto' => 'verde', "fecha"=>  $tempDateNow->format('Y,m,d,H,i,s') );
						
			// $Server->wsSend($id, "Visitor $clientID ($ip) has joined the room. con el String" . json_encode($client) );
				$Server->wsSend($id,  json_encode($messal) );
		} else {

		$rs = array("accion"=> 'ACTIVAR',  "fecha"=> $tempDateNow->format('Y,m,d,H,i,s')) ; 
			$Server->wsSend($id,  json_encode($rs) );

		}
}

// when a client closes or lost connection
function wsOnClose($clientID, $status) {
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	$Server->log( "$ip ($clientID) has disconnected." );

	$Server->log(print_r($Server->listTV, true)); 
	
	logoutTV( $Server->listTV[$clientID] ); 

	$Server->log( " Cerro Session en Base de datos. "); 


	//Send a user left notice to everyone in the room
	foreach ( $Server->wsClients as $id => $client ) {
	$mess =  array(
						'mensaje' => "Visitor $clientID ($ip) has left the room.",
						'moto' => 'verde');	
	$Server->wsSend($id,  json_encode($mess)  );
	
		}
}


require "conexion/tvAccion.php";
require 'conexion/Conexion.php';
require 'conexion/instanciaDB.php';

$bd= ConexionDB::getInstance();

$BisGestion = new AdminBisTV($bd); 

// start the server
$Server = new PHPWebSocket();
$Server->bind('message', 'wsOnMessage');
$Server->bind('open', 'wsOnOpen');
$Server->bind('close', 'wsOnClose');

// for other computers to connect, you will probably need to change this to your LAN IP or external IP,
// alternatively use: gethostbyaddr(gethostbyname($_SERVER['SERVER_NAME']))
$Server->wsStartServer('10.234.133.76', 9300); // ws://10.234.130.55:9300'  127.0.0.1



?>