<?php 

Class InstanciaDB {
   private $_domain;
   private $_userdb;
   private $_passdb;
   private $_hostdb;
   private $_db;

   static $_instance;

   private function __construct(){
     // require 'configDB.php';
   }

   private function __clone(){ }

   public static function getInstance($db){
      if (!(self::$_instance instanceof self)){
         self::$_instance=new self();

      $this->_domain= "";
      $this->_userdb= $db["user"];
      $this->_passdb= $db["password"];
      $this->_hostdb= $db["host"];
      $this->_db= $db["db"];


      }
      
      return self::$_instance;
   }

   public function getUserDB(){
      $var=$this->_userdb;
      return $var;
   }

   public function getHostDB(){
      $var=$this->_hostdb;
      return $var;
   }

   public function getPassDB(){
      $var=$this->_passdb;
      return $var;
   }

   public function getDB(){
      $var=$this->_db;
      return $var;
   }

}

?>