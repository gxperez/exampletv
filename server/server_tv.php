<?php
// prevent the server from timing out
set_time_limit(0);
date_default_timezone_set("America/Santo_Domingo"); 
// include the web sockets server script (the server is started at the far bottom of this file)
require 'class.PHPWebSocket.php';
require 'class.AdminBisTV.php'; 

$glb_Hash = 'F736E021-AAE6-FFBD-CEBE-A64294FC34B1'; 	


$integracionConfig = array('server' => "http://10.234.51.99:8079/GestionVista/Contenido/httpQuitsObtenerPrograma?sckt_hash=F736E021-AAE6-FFBD-CEBE-A64294FC34B1"	, 
"baseURL"=> "http://10.234.51.99:8079/GestionVista/"
);

function wsOnMessage($clientID, $message, $messageLength, $binary) {
	global $Server;
	global $BisGestion; 
	global $integracionConfig; 
	$ip = long2ip( $Server->wsClients[$clientID][6] );
	$timeNow =  new DateTime();	
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
		switch ($varible->accion) {
			case "ACTIVAR":
				$Server->wsClients[$clientID]; 
				$arrayName = array('Mac' => trim($varible->macAdrees),  'Ip'=> trim($ip));
				$Server->listTV[$clientID] = registrarValidarTV($arrayName);

				$Server->log("Cliente $clientID Esta conectado."); 						
				$retornos =	$BisGestion->setHasRefresh(); 
				// $Server->log( print_r($retornos, true) ); 
				$Server->wsSend($clientID,  json_encode(array('accion' => "NOTIFICAR", "Msg"=> "El dispositivo confirmara si esta actualizado", "fecha"=> $timeNow->format('Y,m,d,H,i,s'), "server"=> $integracionConfig["server"], "base_url"=> $integracionConfig["baseURL"],  "fechaActual"=> date("Y-m-d") ) ) );	
				break;	

			case "CONTROLLIDER":
			// Recibe el Mensaje del Key Control del Lider.
			$Server->log("Recibe el mensaje del Lider y la tecla pulsada"); 
			// Aqui va el recorrido para los TV del Grupo que el Corresponde.
			// Aqui Entonaremos la cancion.
			$listaPc = $BisGestion->ObtenerListaTVDelGrupoPorMacLider(trim($varible->macAdrees)); 

			$Server->log(print_r($listaPc, true)); 

			foreach ($Server->listTV as $key => $value) {

				// Para FInes de Prueba
				 // 29443 // Reiniciar el Slider Al Primero.
				if($varible->keyCode == 29443){

					$rsJS = array("accion"=> "CONTROLLIDER", "Msg"=> "", 'keyCode' => $varible->keyCode, "BloqueID"=> $varible->BloqueID, "cIndexC"=> $varible->cIndexC, "cIndexS"=> $varible->cIndexS,"pptKey"=>1, "server"=> $integracionConfig["server"]);

					$Server->log("Esto Existe Y esta OK"); 										
					$Server->log("El Cambio Por Prueba Sip");
					$Server->log(print_r($value, true));
					$Server->wsSend($key, json_encode($rsJS)); 

				}


				if(array_key_exists($value["Mac"], $listaPc)) {
					// 
					$rsJS = array("accion"=> "CONTROLLIDER", "Msg"=> "", 'keyCode' => $varible->keyCode, "BloqueID"=> $varible->BloqueID, "cIndexC"=> $varible->cIndexC, "cIndexS"=> $varible->cIndexS,"pptKey"=>$varible->pptKey, "server"=> $integracionConfig["server"]);

					$Server->log("Esto Existe Y esta OK"); 										
					$Server->log("Este es el PC Encendida");
					$Server->log(print_r($value, true));
					$Server->wsSend($key, json_encode($rsJS));
				}
			}



			// {"macAdrees":"0800279b3e8c","Tipo":"TV","accion":"CONTROLLIDER","keyCode":5,"BloqueID":"1","cIndexC":0,"cIndexS":1,"pptKey":2}






			break;
			case "TIMEQUERY":
			//	$Server->wsSend(); 
			break; 

			case 'BROADCAST':
				$Server->log("BROADCAST=> Listen"); 
				$rsJSB = array('accion' => "BROADCAST",  "Msg"=> "" ); 
				$Server->wsSend($clientID, json_encode($rsJSB)); 


				break;
			default:
			//	# code...
			
				break;
		}		

		return false; 
	}

	
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

					   } else {
					   	
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
	global $integracionConfig;
	$ip = long2ip( $Server->wsClients[$clientID][6] );
	
	$tempDateNow =  new DateTime(); 
	$Server->log( "$ip  ($clientID) has connected." . json_encode($Server->wsClients[$clientID]));	


	//Send a join notice to everyone but the person who joined
	foreach ( $Server->wsClients as $id => $client )		

		if ( $id != $clientID ) {
		$messal =  array(
						'mensaje' => '"Visitor $clientID ($ip) has joined the room. con el String"',
						'moto' => 'verde', "fecha"=>  $tempDateNow->format('Y,m,d,H,i,s') );
						
		$Server->log( "Mensaje para Clientes Diferentes. " ); 
			//	$Server->wsSend($id,  json_encode($messal) );
		} else {

		$rs = array("accion"=> 'ACTIVAR',  "Msg"=> "El dispositivo Esta conectado", "fecha"=> $tempDateNow->format('Y,m,d,H,i,s'), "server"=> $integracionConfig["server"]) ; 
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
//  $Server->wsStartServer('10.234.51.99', 9300); // ws://10.234.130.55:9300'  127.0.0.1 // 10.234.133.76

 $Server->wsStartServer('10.234.133.76', 9300); // ws://10.234.130.55:9300'  127.0.0.1

?>