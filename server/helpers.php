<?php

try
{	
	
	function registrarValidarTV( $dispositivoEnt){
		 $DispositivoID = 0;
		 global $bd;

		 $result = $bd->ejecutar("select count(*) as Cantidad, MAX(DispositivoID) as DispositivoID, uuid() as uid from dispositivo where Mac = '{$dispositivoEnt["Mac"]}'" );
		 

		 $row = $bd->obtener_fila($result, 0); 

		 $DispositivoID = $row['DispositivoID']; 

		 $consultaGruposPertenece = $bd->ejecutar("select GrupoID, DispositivoID, EsLider from Grupo_Tv where Estado = 1 and DispositivoID = {$row['DispositivoID']}" );
		 $listaGrupo =  $bd->obtener_fila($consultaGruposPertenece, 0); 
		 

			if($row['Cantidad'] ==  0){
				// Hay Que Crear el Televisor
				$date = date("Y-m-d H:i:s"); 

				$query = "INSERT INTO `bis_gestionvista`.`dispositivo` (`Nombre`, `Descripcion`, `DispositivoTipo`, `Marca`, `Estado`, `Mac`, `IP`, `FechaCrea`, `UltimaSesion`) VALUES ('TV-{$dispositivoEnt['Mac']}', 'Regitrado en el Servidor TV-{$dispositivoEnt['Mac']}', '1', 'Samsung', '1', '{$dispositivoEnt['Mac']}', '{$dispositivoEnt['Ip']}', '$date', '$date');"; 

					$stmt = $bd->ejecutar($query); 
					$DispositivoID = $bd->lastID();
			}
			
			$dateGen = date("Y-m-d H:i:s");

			$stmt = $bd->ejecutar("INSERT INTO `bis_gestionvista`.`dispositivo_log` (`DispositivoID`, `Estatus`, `FechaHoraInicio`, `FechaCrea`) VALUES ($DispositivoID, '1', '$dateGen', '$dateGen');");

		
			$stmt = $bd->ejecutar("UPDATE `bis_gestionvista`.`session_dispositivo_log` SET `Estado`= -1 WHERE `Mac`='{$dispositivoEnt['Mac']}';"); 

			$stmt = $bd->ejecutar("INSERT INTO `bis_gestionvista`.`session_dispositivo_log` (`DispositivoID`, `Mac`, `Ip`, `Estado`, `uid`) VALUES ('$DispositivoID', '{$dispositivoEnt['Mac']}', '{$dispositivoEnt['Ip']}', '1', '{$row['uid']}');");

				$row["DispositivoID"] = $DispositivoID; 
				$row["Mac"]	= $dispositivoEnt['Mac'];				
				$row["Ip"]	= $dispositivoEnt['Ip'];
				$row["listGrupos"] = $listaGrupo;
				return $row;
	}

	function logoutTV($dispositivoEnt){
			global $bd; 					
			$date = date("Y-m-d H:i:s"); 

					$stmt = $bd->ejecutar("UPDATE `bis_gestionvista`.`session_dispositivo_log` SET `Estado`='-1' WHERE `Mac`='{$dispositivoEnt['Mac']}';"); 

					 $bd->ejecutar("UPDATE `bis_gestionvista`.`dispositivo_log` SET `Estatus`='0', `FechaHoraFin`='{$date}' WHERE `Estatus`='1' and `DispositivoID` = {$dispositivoEnt['DispositivoID']};");

					return $stmt; 
	}


// utilities.php
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
				
				var_dump($integracionConfig); 
// La fe no Admite Dudas.

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

// 
/*
{modo:"flash", showCategory: true,
		 			 	categoryText: "Destilados",
		 	  			styleCat: "background: red; color: white;",
		 	  			items: ["Esta es Otra Forma Paso del primer mensaje Enviado desde el Servidor", 'Solo una prueba de calidad']
		 			};

			*/
			// {"macAdrees":"0800279b3e8c","Tipo":"TV","accion":"CONTROLLIDER","keyCode":5,"BloqueID":"1","cIndexC":0,"cIndexS":1,"pptKey":2}


			break;
			case "TIMEQUERY":
			//	$Server->wsSend(); 
			break; 

			case 'BROADCAST':
				$Server->log("BROADCAST=> Listen"); 

				$arregloRes= array('modo' => "normal",
				 "showCategory"=>true, 
				 "categoryText"=> "EL BISFORSALES",
		 	  	 "styleCat"=> "background: blue; color: white;",
		 	  	  "subCategoryText" => "Orientacion del Dia", 
				"styleSubCat"=> "background: green; color: white;",
		 	  	 "items"=> array("Buscando las Orientaciones del día en Cerveza", "Segundo Equipo", "Tecero de todos los Equipos", "Cuarto Mensjae del Bis") 
				 );

				$rsJSB = array('accion' => "BROADCAST",  "Msg"=> "", "duracion"=> 25000, "data"=> $arregloRes ); 
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


// when a client connects
function wsOnOpen($clientID)
{
	global $Server;
	global $integracionConfig;
	$ip = long2ip( $Server->wsClients[$clientID][6] );
	
	$tempDateNow =  new DateTime(); 
	$Server->log( "$ip  ($clientID) has connected.");	

		// . json_encode($Server->wsClients[$clientID]))

	//Send a join notice to everyone but the person who joined
	foreach ( $Server->wsClients as $id => $client )		

		if ( $id != $clientID ) {
		$messal =  array(
						'mensaje' => '"Smart TV $clientID ($ip) se ha unido a la red"',
						'moto' => 'verde', "fecha"=>  $tempDateNow->format('Y,m,d,H,i,s') );

		$Server->log( "Mensaje para Clientes Diferentes. " ); 
			
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
	// $Server->log(print_r($Server->listTV, true)); 	

	if(array_key_exists($clientID, $Server->listTV) ){
		logoutTV( $Server->listTV[$clientID] ); 
		$Server->log( "TV desconnetecd. "); 
		$Server->log( "Total TV online: ". count($Server->listTV) ); 		
	}

	//Send a user left notice to everyone in the room
	foreach ( $Server->wsClients as $id => $client ) {
		$mess =  array(
						'mensaje' => "Visitor $clientID ($ip) has left the TV Red.",
						'moto' => 'verde');	
		$Server->wsSend($id,  json_encode($mess)  );
	
		}
}

/*
stream_set_blocking(STDIN, 0);
$csv_ar = fgetcsv(STDIN);

if (is_array($csv_ar)){ 
  print_r($csv_ar); 
} 
*/


}
catch(Exception $ex)
{
    //Return error message
	$jTableResult = array();
	$jTableResult['Result'] = "ERROR";
	$jTableResult['Message'] = $ex->getMessage();
	print json_encode($jTableResult);
}

?>