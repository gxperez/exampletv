<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class WebServices extends MY_Controller {

	/**
	 * Index Page for this controller.
	 	@autor:	Grequis Xavier Perez Fortuna.
	 */
	public function index()
	{	

	// Unificar y llegar a un mundo de Amor.		
	}

	public function GetTwees(){
		if( $this->input->get("Mac")){
			$mac = $this->input->get("Mac"); 
			echo "Preparacion del List de Elementos"; 
			echo ' <style> ul {
  list-style-image: url("https://mdn.mozillademos.org/files/11981/starsolid.gif")  </style> '." <ul>    <li>Item 1</li> <li>ITems</li></ul>"; 

		} else {
			echo "Lisa de Elementos que Estaran Visibles en TVs."; 
		}
	}


	public function pruebaLinea(){

$confiMsg = array( array('UrlFuente' => "Mi Pagina.com" , 'TiempoRequest'=> 0 ), array('UrlFuente' => "Localhost" , 'TiempoRequest'=> 1 )); //$BisGestion->ObtenerListaCOnfiguracionMensajes(); 
			$setLimeLik = array();
			
			// Set Fechas y Horas para el Recorrido en Minutos.
			foreach ($confiMsg as $key => $value) {
			// Lista de elementos.
				$fecha 	= date("Y-m-d H:i");
				$newKey = strtotime ( "+ {$value['TiempoRequest']} minute" , strtotime ( $fecha ) ) ;// date($fecha, (strtotime ("+4 minute")));
				$newKey = date('Y-m-d H:i', $newKey);
				$setLimeLik[$value["UrlFuente"]]= array('minutos' =>$newKey , "tiempo"=> $value['TiempoRequest']);
			}

			echo date("Y-m-d Hi")."_INIANDO <br>"; 

			$bool = true;
			while ($bool) {
				
			 	echo  date("YmdHi"). "<br>"; 

				foreach ($setLimeLik as $key => $value) {

					if( date("YmdHi") == date("YmdHi", strtotime($value ) )){
						// Extraer el Link que es el $key
						// Aqui no se puede utilizar estas palabras.
						echo "$key $value  <br>"; 
						echo date("Y-m-d-H:i"); 
						if($key == 1){
							$bool = false;
						}
						
					}
				}
			}


	}


	public function pushMsgFVs(){
		// Validacion del Has

		$minutos = 15;
$fecha 	= date("Y-m-d H:i:s");
$newKey = strtotime ( "+{$minutos} minute" , strtotime ( $fecha ) ) ;// date($fecha, (strtotime ("+4 minute")));
$newKey = date('Y-m-d H:i:s', $newKey);
//$nuevafecha = strtotime ( '+13 minute' , strtotime ( $fecha ) ) ;
//$nuevafecha = strtotime ( '+30 second' , strtotime ( $fecha ) ) ;
// $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

$arrayName = array('TipoMensaje'=>  1, "AlcanceMensaja"=> 0, // Entero {0: Todo los TV, sin Filtro, 1: La regional, 2: Su centro, 3: Solo su televisor}
    "GuidFvOrigen"=> "79EFB5BF-AD59-4FD3-88D9-0014A278C32C",
    "FuerzaVentaDescripcion" =>"DO0382",
   "GuidFvCentroOrigen"=>"8B580525-E611-49A3-B2BD-45BD5FBC2693",
  "FechaUltimaActualizacion"=> date("Y-m-d h:i:s"),
  "CategoriaKPI"=>"Volumen, Cobertura, etc",
  "NombreKpi" => "Trimarca, Cerveza, etc",
   "Mensaje" => null, //Es varchar(max) Si viene Null, tomaremos los valores de Meta y valor como mensaje. (Si bien con datos omite Meta y valor coloca el texto)
"Meta"=> 540, 
"Valor" => 570, 
"Comentario"=> "Texto opcional Ejemplo." );

$avv = array();
$avv[] = $arrayName; 

echo json_encode($avv); 



		exit();
		if( $this->input->get("k_hash")){				
				$hash = $this->input->get("k_hash");
				if($hash == '7019FD55-E87A-481D-BE89-BDDBCC75FA9B'){	
					// Aqui va ha recibir mensajes Limpioos de KPIS
					echo json_encode(array('msg' => "ok", "estatus"=> 1 )); 
					return 0; 
				}
				echo json_encode(array('msg' => "Hash incorrecto. ", "estatus"=> 0 )); 
		}	
		echo json_encode(array('msg' => "Error", "estatus"=> 0 )); 
	}


	public function postMsgFVs(){
		// Validacion del Has
		if( $this->input->post("k_hash")){				
				$hash = $this->input->get("k_hash");
				if($hash == '7019FD55-E87A-481D-BE89-BDDBCC75FA9B'){
					// Aqui va ha recibir mensajes Limpioos de KPIS.
				}			
		}
		echo "postMsg"; 
	}


// Envio de Mensajes al Servidor desde Https

	 private function connect()
    {
    	 $ipServer = gethostbyname(gethostname()); 

        $sock = @fsockopen($ipServer, "9300", $errno, $errstr, 2);
        if(!$sock){
            console("| El servicio no Esta Disponible.");
            console("| Estado: Inactivo.");
            exit();  
        }
        fwrite($sock, $this->head);
        $headers = fread($sock, 2000);
        $this->wss = $sock;        
    }

     public function verificarPortSocket(){
         $ipServer = gethostbyname(gethostname()); 
        $fp = @fsockopen($ipServer, "9300", $errno, $errstr, 30);

        if (!$fp) {
            return true;
        } else {        
            fclose($fp);
            return false;
        }
        return false;        
    }

    private function sendMsg($method){
        
        $ipServer = gethostbyname(gethostname()); 


  
        $local = "http://".$ipServer;         
        $this->head =   "GET / HTTP/1.1\r\n" .
                        "Upgrade: websocket\r\n" .
                        "Connection: Upgrade\r\n" .
                        "Host: ".$ipServer."\r\n" .
                        "Origin: ".$local."\r\n" .
                        "Sec-WebSocket-Key: TyPfhFqWTjuw8eDAxdY8xg==\r\n" .
                        "Sec-WebSocket-Version: 13\r\n"; 

        $this->head .= "Content-Length: ".strlen($method)."\r\n\r\n";
        $this->connect();       
        fwrite($this->wss, $this->hybi10Encode($method));
        $wsdata = fread($this->wss, 2000);

            fclose($this->wss);
            $this->wss = NULL;
        return $this->hybi10Decode($wsdata);
    }

     private function hybi10Decode($data)
    {
        $bytes = $data;
        $dataLength = '';
        $mask = '';
        $coded_data = '';
        $decodedData = '';
        $secondByte = sprintf('%08b', ord($bytes[1]));
        $masked = ($secondByte[0]=='1') ? true : false;
        $dataLength = ($masked===true) ? ord($bytes[1]) & 127 : ord($bytes[1]);
        if ($masked===true)
        {
            if ($dataLength===126)
            {
                $mask = substr($bytes, 4, 4);
                $coded_data = substr($bytes, 8);
            }
            elseif ($dataLength===127)
            {
                $mask = substr($bytes, 10, 4);
                $coded_data = substr($bytes, 14);
            }
            else
            {
                $mask = substr($bytes, 2, 4);
                $coded_data = substr($bytes, 6);
            }
            for ($i = 0; $i<strlen($coded_data); $i++)
                $decodedData .= $coded_data[$i] ^ $mask[$i % 4];
        }
        else
        {
            if ($dataLength===126)
                $decodedData = substr($bytes, 4);
            elseif ($dataLength===127)
                $decodedData = substr($bytes, 10);
            else
                $decodedData = substr($bytes, 2);
        }
        return $decodedData;
    }

    private function hybi10Encode($payload, $type = 'text', $masked = true)
    {
        $frameHead = array();
        $frame = '';
        $payloadLength = strlen($payload);
        switch ($type)
        {
            case 'text' :
                // first byte indicates FIN, Text-Frame (10000001):
                $frameHead[0] = 129;
                break;
            case 'close' :
                // first byte indicates FIN, Close Frame(10001000):
                $frameHead[0] = 136;
                break;
            case 'ping' :
                // first byte indicates FIN, Ping frame (10001001):
                $frameHead[0] = 137;
                break;
            case 'pong' :
                // first byte indicates FIN, Pong frame (10001010):
                $frameHead[0] = 138;
                break;
        }
        // set mask and payload length (using 1, 3 or 9 bytes)
        if ($payloadLength>65535)
        {
            $payloadLengthBin = str_split(sprintf('%064b', $payloadLength), 8);
            $frameHead[1] = ($masked===true) ? 255 : 127;
            for ($i = 0; $i<8; $i++)
                $frameHead[$i + 2] = bindec($payloadLengthBin[$i]);
            // most significant bit MUST be 0 (close connection if frame too big)
            if ($frameHead[2]>127)
            {
                $this->close(1004);
                return false;
            }
        }
        elseif ($payloadLength>125)
        {
            $payloadLengthBin = str_split(sprintf('%016b', $payloadLength), 8);
            $frameHead[1] = ($masked===true) ? 254 : 126;
            $frameHead[2] = bindec($payloadLengthBin[0]);
            $frameHead[3] = bindec($payloadLengthBin[1]);
        }
        else
            $frameHead[1] = ($masked===true) ? $payloadLength + 128 : $payloadLength;
        // convert frame-head to string:
        foreach (array_keys($frameHead) as $i)
            $frameHead[$i] = chr($frameHead[$i]);
        if ($masked===true)
        {
            // generate a random mask:
            $mask = array();
            for ($i = 0; $i<4; $i++)
                $mask[$i] = chr(rand(0, 255));
            $frameHead = array_merge($frameHead, $mask);
        }
        $frame = implode('', $frameHead);
        // append payload to frame:
        for ($i = 0; $i<$payloadLength; $i++)
            $frame .= ($masked===true) ? $payload[$i] ^ $mask[$i % 4] : $payload[$i];
        return $frame;
    }


}


?>