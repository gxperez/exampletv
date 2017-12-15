<?php
/*
Clase principal del programa del Servidor.
 Aplication para manejar los recursos de los Socket.
*/
class Application
{
    private static $instances = array();
    private $_clients = array();
    private $isRuning = false;
    public  $_extract = false;
    private $head;
    private $wss;
    private function __construct()
    {
    }

    final private function __clone()
    {
    }

    /*
     *Singleton.
     */
    final public static function getInstance()
    {    
        $calledClassName = get_called_class();
        if (!isset(self::$instances[$calledClassName])) {
            self::$instances[$calledClassName] = new $calledClassName();
        }
        return self::$instances[$calledClassName];
    }

    public function verificarPortSocket(){
        global $config;
        $fp = @fsockopen($config["websocket"]["ip"], $config["websocket"]["port"], $errno, $errstr, 30);

        if (!$fp) {
            return true;
        } else {        
            fclose($fp);
            return false;
        }
        return false;        
    }

    private function connect()
    {
        global $config;
        $sock = @fsockopen($config["websocket"]["ip"], $config["websocket"]['port'], $errno, $errstr, 2);
        if(!$sock){
            console("| El servicio no Esta Disponible.");
            console("| Estado: Inactivo.");
            exit();  
        }
        fwrite($sock, $this->head);
        $headers = fread($sock, 2000);
        $this->wss = $sock;        
    }

    public function sendMsg($method){
         global $config;
$direccion = $config["websocket"]["ip"]; // gethostbyname("www.example.com");
// socket_create(AF_INET, SOCK_STREAM, SOL_TCP)
  
        $local = "http://".$config["websocket"]["ip"];         
        $this->head =   "GET / HTTP/1.1\r\n" .
                        "Upgrade: websocket\r\n" .
                        "Connection: Upgrade\r\n" .
                        "Host: ".$config["websocket"]["ip"]."\r\n" .
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

    private function detenerServicio(){
        $mesaje = array('clienteSessionID' => -1,
            "macAdrees"=> "0",
            "Tipo"=> "SERVER_REMOTE",
            "accion"=> "",
            "fecha"=> date("Y-m-d"),
            "accion"=> "CLOSESERVER",
            "mensaje"=> "El administrador esta forzando detener el Servicio.");

            $vvr =  $this->sendMsg(json_encode($mesaje));
    }

    private function menuOption(){
        global $config;
        // Funciones del servicio
        console("--------------------------------------------------------------------");
        console("| [0]: - Salir \n | [gv get-report]:   Obtiene un reporte de conectividad Gestion Vista.");        
        console("| [gv server-exit]:  Cierra la aplicacion corriendo en puerto: {$config["websocket"]["port"]} ");
        console("| [-help]:  Ayuda.");      
        console("--------------------------------------------------------------------");

    }

    public function Run(){
       
        // varible de confguracion General.
        console(" =========== Programa de Socket Para Red Smart TV. ==================");
        if($this->verificarPortSocket()){
            $this->runSocket();
        } else {

        console(" ********************************************************************");
        console("\n Notice: El puerto ya esta corriendo un script.");        
        $this->menuOption();
        $bucle = true;
        while ($bucle) {

            stream_set_blocking(STDIN, 0);
            console("Favor Escriba el Comando: "); 
            $csv_ar = fgetcsv(STDIN);


            switch ($csv_ar[0]) {
                case "0":
                    exit(0);
                    break;
                case "gv server-exit":
                    $this->detenerServicio(); 
                    $bucle = false; 
                    break;
                case "gv get-report":
                  //  $this->detenerServicio(); 
                console("Buscando reporte..."); 
                    $mesaje = array('clienteSessionID' => -1,
            "macAdrees"=> "0",
            "Tipo"=> "SERVER_REMOTE",
            "accion"=> "",
            "fecha"=> date("Y-m-d"),
            "accion"=> "REPORTE",
            "mensaje"=> "El administrador esta forzando detener el Servicio.");
            $resultado =  $this->sendMsg(json_encode($mesaje));

            $this->printReport(json_decode($resultado)); 

                    break;

                case "-help":
                    $this->menuOption();
                    break; 

                 case "gv -restart" :
                    $mesaje = array('clienteSessionID' => -1,
                        "macAdrees"=> "0",
                        "Tipo"=> "SERVER_REMOTE",
                        "accion"=> "",
                        "fecha"=> date("Y-m-d"),
                        "accion"=> "RESETSERVER",
                        "mensaje"=> "El administrador esta forzando detener el Servicio.");
                  //      $resultado =  $this->sendMsg(json_encode($mesaje));
                    break;               
                default:
                console("Notice: Comando invalido. si necesita ayuda escriba -help ");                    
                    break;
            }
        }

        }
    }

    private function printReport($obj){
        console("");
        console("--------------------------------------");        
        console("|   REPORT ACTIVIDAD GESTION VISTA    |");
        console("-------------------------------------");
        console("| -Fecha: ". $obj->fecha );
        console("| -Estado: ". $obj->estatus );        
        console("| -Total Conetados: ". $obj->total_conected );
        console("| -Smart TV: ". $obj->total_tv );
        console("| -Web Session: ". ($obj->total_conected - $obj->total_tv) );
        console("|-------------------------------------|"); 
    }


    private function runSocket(){
        global $config;
        global $Server;
        global $bd;
        global $BisGestion; 

        $isRuning = true;
        $this->isRuning = true;
        while ($isRuning) {
            $bd= ConexionDB::getInstance($config["database"]);
            $BisGestion = new AdminBisTV($bd); 
            try {
                $Server = new PHPWebSocket();
                $Server->bind('message', 'wsOnMessage');
                $Server->bind('open', 'wsOnOpen');
                $Server->bind('close', 'wsOnClose');
                $Server->wsStartServer($config["websocket"]["ip"], $config["websocket"]["port"]);
            } catch (Exception $e) {    
                echo 'Exception: - ',  $e->getMessage(), " \n ";
                $bd->reset(); 
                console("Reiniciando..");                    
                $isRuning = true;
                continue; 
            }
        }
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


function console($msg){
    echo " {$msg} \n"; 
}
?>