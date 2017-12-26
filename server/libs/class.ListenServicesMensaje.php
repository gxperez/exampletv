<?php 
// Class Listen Messeangers list

class ListenServicesMensaje {


	public function run(){
		global $config;
        global $Server;
        global $bd;
        global $BisGestion; 

        // Obtien las instania de la base de datos.        
        $isRuning = true;
        while ($isRuning) {
            $bd= ConexionDB::getInstance($config["database"]);
            $BisGestion = new AdminBisTV($bd); 

            try {
			$confiMsg = $BisGestion->ObtenerListaCOnfiguracionMensajes(); 
			$setLimeLik = array();			
			// Set Fechas y Horas para el Recorrido en Minutos.	

		 echo print_r($confiMsg, true);
		 echo "\n"; 	



			foreach ($confiMsg as $key => $value) {
			// Lista de elementos.
				$fecha 	= date("Y-m-d H:i");

				$newKey = strtotime ( "+ {$value['UrlFuente']} minute" , strtotime ( $fecha ) );// date($fecha, (strtotime ("+4 minute")));
				$newKey = date('YmdHi', $newKey);
				$setLimeLik[$value["UrlFuente"]]= $newKey;
			}

			$bool = true;
			while ($bool) {
				foreach ($setLimeLik as $key => $value) {					
					if(date("YmdHi") == date("YmdHi", $value)){
						// Extraer el Link que es el $key
					}
				}
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

?>