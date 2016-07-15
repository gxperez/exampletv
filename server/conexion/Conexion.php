<?php 

Class ConexionDB{

   private $servidor;
   private $usuario;
   private $password;
   private $base_datos;
   private $link;
   private $stmt;
   private $array;
   static $_instance;

   public $argServer; 

   private function __construct(){
      $this->setConexion();
      $this->conectar();
   }

   /*Método para establecer los parámetros de la conexión*/
   private function setConexion(){
      $conf = InstanciaDB::getInstance();
      $this->servidor=$conf->getHostDB();
      $this->base_datos=$conf->getDB();
      $this->usuario=$conf->getUserDB();
      $this->password=$conf->getPassDB();
   }

   /*Evitamos el clonaje del objeto. Patrón Singleton*/
   private function __clone(){ }

   /*Función encargada de crear, si es necesario, el objeto. Esta es la función que debemos llamar desde fuera de la clase para instanciar el objeto, y así, poder utilizar sus métodos*/
   public static function getInstance(){
      if (!(self::$_instance instanceof self)){
         self::$_instance=new self();
      }
         return self::$_instance;
   }

   /*Realiza la conexión a la base de datos.*/
   private function conectar(){
      $this->link=mysqli_connect($this->servidor, $this->usuario, $this->password);
      mysqli_select_db($this->link, $this->base_datos);
    //  @mysql_query("SET NAMES 'utf8'");
   }

   /*Método para ejecutar una sentencia sql*/
   public function ejecutar($sql){
    //  $this->stmt=mysql_query($sql,$this->link);
	   $this->stmt = mysqli_query($this->link, $sql); 
      return $this->stmt;
   }

   /*Método para obtener una fila de resultados de la sentencia sql*/
   public function obtener_fila($stmt,$fila){
      

      if ($fila==0){
       //  $this->array=mysql_fetch_array($stmt);         

		  $this->array=mysqli_fetch_array($stmt, MYSQLI_ASSOC);
		 
      }else{
       //  mysqli_data_seek($stmt,$fila);
         $this->array=mysqli_fetch_array($stmt, MYSQLI_ASSOC);
      }
      return $this->array;
   }

   //Devuelve el último id del insert introducido
   public function lastID(){
      return mysqli_insert_id($this->link);
   }

   public function conectarServerInfo(){

      // Conxion del Json para Distribuirlo por 

   }


}
?>