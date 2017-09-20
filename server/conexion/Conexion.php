<?php 
define("MYSQL_CONN_ERROR", "Unable to connect to database."); 
mysqli_report(MYSQLI_REPORT_STRICT); 

Class ConexionDB{

   private $servidor;
   private $usuario;
   private $password;
   private $base_datos;
   private $link = false;
   private $stmt;
   private $array;

   static $_configAttr;
   static $_instance;

   public $argServer; 

   private function __construct(){
      try{
         $this->setConexion();
         $this->conectar();
      } catch (Exception $e) { 
         echo $e->errorMessage(); 
      } 
   }

   /*Método para establecer los parámetros de la conexión*/
   private function setConexion(){      
      $conf = InstanciaDB::getInstance(self::$_configAttr);
      

      $this->servidor=$conf->getHostDB();
      $this->base_datos=$conf->getDB();
      $this->usuario=$conf->getUserDB();
      $this->password=$conf->getPassDB();
   }

   /*Evitamos el clonaje del objeto. Patrón Singleton*/
   private function __clone(){ }

   /*Función encargada de crear, si es necesario, el objeto. Esta es la función que debemos llamar desde fuera de la clase para instanciar el objeto, y así, poder utilizar sus métodos*/
   public static function getInstance($dt){
      self::$_configAttr = $dt; 
      if (!(self::$_instance instanceof self)){
         self::$_instance= new self();
      }
         return self::$_instance;
   }

   /*Realiza la conexión a la base de datos.*/
   private function conectar(){  
   try {      
      $this->link= new  mysqli($this->servidor, $this->usuario, $this->password, $this->base_datos);
      //mysqli_connect($this->servidor, $this->usuario, $this->password);
      // mysqli_select_db($this->link, $this->base_datos);    
      // $this->link->select_db($this->link, $this->base_datos);  
      } catch (mysqli_sql_exception $e) { 
         throw $e; 
      }     
      
   }

   /*Método para ejecutar una sentencia sql*/
   public function ejecutar($sql){
    //  $this->stmt=mysql_query($sql,$this->link);
      if(!$this->link){
         $this->conectar(); 
      }

	   $this->stmt = $this->link->query($sql);  // mysqli_query($this->link, $sql);       
      return $this->stmt;
   }

   /*Método para obtener una fila de resultados de la sentencia sql*/
   public function obtener_fila($stmt,$fila){         
      if(!$stmt){
         return array();
      }
		$this->array = $stmt->fetch_array(MYSQLI_ASSOC); // or die(mysql_error());
      return $this->array;
   }

   //Devuelve el último id del insert introducido
   public function lastID(){         
      return $this->link->insert_id;  //  mysqli_insert_id($this->link);

   }

   public function conectarServerInfo(){
      // Conxion del Json para Distribuirlo por 
   }


   public function desconectarBD(){
      // Cerrar y Destruir la Conexion DBA.
       mysqli_close ( $this->link ); 
       $this->link = false; 
   }


}
?>