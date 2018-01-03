<?php 
// Class Listen Messeangers list
class ListenServicesMensaje {
	public function leer($url){
		global $config;

		$json = file_get_contents($url);
		$lista = json_decode($json); 

		 $lbd= ConexionDB::getInstance($config["database"]);
         $LBisGestion = new AdminBisTV($lbd);
         // Aqui va el recorrido del Texto Para Insertar los Registros Por Fuerza De venta.
        $listaInsertDB = ""; 
		$LBisGestion->removeFromURL($url);
         foreach ($lista as $key => $value) {
         	// Insercion del Registro en Cuestion.
         	$listaInsertDB = $LBisGestion->setListRecpecionMensaje($value, $url);
         	  $LBisGestion->saved($listaInsertDB);  
         }
         // Aqui se insertara el Enlace...
  	}

	public function run(){
		global $config;
        global $Server;
        global $bd;
        global $BisGestion; 

        // Obtien las instania de la base de datos.        
        $isRuning = true;

        while ($isRuning) {
// Inicio de la Instancia a base de datos.
            $bd= ConexionDB::getInstance($config["database"]);
            $BisGestion = new AdminBisTV($bd);
            // Fin de la instancia a Base de datos.
            try {
			$confiMsg = $BisGestion->ObtenerListaCOnfiguracionMensajes(); 
			$setLimeLik = array();			
			// Set Fechas y Horas para el Recorrido en Minutos.			
			foreach ($confiMsg as $key => $value) {
			// Lista de elementos.
				$fecha 	= date("Y-m-d H:i");
				$newKey = strtotime ( "+ {$value['TiempoRequest']} minute" , strtotime ( $fecha ) );
				$newKey = date('YmdHi', $newKey);
				$setLimeLik[$value["UrlFuente"]]= array('timer' => $newKey, "TiempoRequest"=> $value['TiempoRequest'] );
			}

			$bool = true;
			$vol = 0;
			while ($bool) {

				foreach ($setLimeLik as $key => $value) {					
					if(date("YmdHi") == $value["timer"] ){
						// Extraer el Link que es el $key e insertar la Informacion.
						echo "\n Coinciden.. $key \n"; 
						$nt = strtotime ( "+ {$value['TiempoRequest']} minute" , strtotime (date("Y-m-d H:i")));
						$nt = date('YmdHi', $nt);
						$setLimeLik[$key]["timer"] = $nt; 
						// GET Request Parse intra net.
						echo $key."\n";
						$this->leer($key); 
						sleep(5);
					} else {
						echo "Valor = ". $value["timer"] . " || ".  date("YmdHi"). " == " .$value["timer"] . " \n";
						echo $key. "\n ";  
					}
				}

				$vol++; 
				if($vol == 5){
					$bool = false; 
					echo "\n  Reset instancia............\n "; 
				}
					sleep(5);

				// Fragmento para el Envio de Solicitudes.
			}                
            } catch (Exception $e) {    
                echo 'Exception: - ',  $e->getMessage(), " \n ";
                $bd->reset(); 
                console("Reiniciando, el servicos de los socket");                    
                $isRuning = true;
                continue; 
            }
        }
	}

}



class SendServicesMensaje {

	public function Run(){

		global $config;
        global $Server;
        global $bd;
        global $BisGestion; 

        // Obtien las instania de la base de datos.        
        $isRuning = true;

        while ($isRuning) {
		// Inicio de la Instancia a base de datos.
          $bd= ConexionDB::getInstance($config["database"]);
          $BisGestion = new AdminBisTV($bd);

          


          sleep((1*5));
          // Cade Tiempo Envia la Cola de los Mensajes.
        }
	}

}

?>