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
		
    }

    public function Run(){
    	// varible de confguracion General.
    	return 0;
    	console(" =========== Programa de Socket Para Red Smart TV. ==================");
    	if($this->verificarPortSocket()){
    		$this->runSocket();
    	} else {
    	console("Notice: El puerto ya esta corriendo un script."); 

	    	stream_set_blocking(STDIN, 0);
			console("Favor Escriba el Comando: "); 
			$csv_ar = fgetcsv(STDIN);
    	}
    }


    private function runSocket(){
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
			    $isRuning = true;
			}
		}
    }
}


function console($msg){
	echo " {$msg} \n"; 
}
?>