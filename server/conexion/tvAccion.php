<?php
try
{

	
	
	function registrarValidarTV( $dispositivoEnt){
		 $DispositivoID = 0; 


		 global $bd; 
		 $result = $bd->ejecutar("select count(*) as Cantidad, MAX(DispositivoID) as DispositivoID, uuid() as uid from dispositivo where Mac = '{$dispositivoEnt["Mac"]}';" );

		 $row = $bd->obtener_fila($result, 0); 

		 $DispositivoID = $row['DispositivoID']; 

		 $consultaGruposPertenece = $bd->ejecutar("select GrupoID, DispositivoID from Grupo_Tv where Estado = 1 and DispositivoID = {$row['DispositivoID']};" );
		 $listaGrupo =  $bd->obtener_fila($consultaGruposPertenece, 0); 
		 

			if($row['Cantidad'] ==  0){
				// Hay Que Crear el Televisor
				$date = date("Y-m-d H:i:s"); 

				$query = "INSERT INTO `bis_gestionvista`.`dispositivo` (`Nombre`, `Descripcion`, `DispositivoTipo`, `Marca`, `Estado`, `Mac`, `IP`, `FechaCrea`, `UltimaSesion`) VALUES ('TV-{$dispositivoEnt['Mac']}', 'Regitrado en el Servidor TV-{$dispositivoEnt['Mac']}', '1', 'Samsung', '1', '{$dispositivoEnt['Mac']}', '{$dispositivoEnt['Ip']}', '$date', '$date');"; 

					$stmt = $bd->ejecutar($query); 
					$DispositivoID = $bd->lastID();
			}

			// Insercion del Log en e Procedure 
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
			
		// Cerrar 
			$date = date("Y-m-d H:i:s"); 

					$stmt = $bd->ejecutar("UPDATE `bis_gestionvista`.`session_dispositivo_log` SET `Estado`='-1' WHERE `Mac`='{$dispositivoEnt['Mac']}';"); 

					 $bd->ejecutar("UPDATE `bis_gestionvista`.`dispositivo_log` SET `Estatus`='0', `FechaHoraFin`='{$date}' WHERE `Estatus`='1' and `DispositivoID` = {$dispositivoEnt['DispositivoID']};"); 

			

					return $stmt; 
	}

	/*

	$arrayName = array('Mac' => '2.fddf2.3dfd.35',  'Ip'=> '2.3636.336');
	$arrayName = registrarValidarTV($arrayName); 
	logoutTV($arrayName); 
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