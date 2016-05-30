<?php

class Page {

	public $title;
	public $descripcion;

	public function __construct($title, $descripcion)
	{
		$this->title = $title;
		$this->descripcion = $descripcion;
	}

}


class Portal_model extends CI_Model {

	private $listaModulos = array('login' =>"Iniciar Session" );	
	private $listaMenu = array();	

        public function __construct()
        {
                parent::__construct();
        }

        public function getPages(){

        	$pages = new Page("Gestion a la Vista", "Gestion de Contenidos para monitores de ventas");

        	return $pages;
        }


        public function generarMenu($idRol){


			$this->listaMenu = array(

			 array('id' => 0 ,
			 	'label' => Text::_("Home") ,
			 	'link' => "#",
			 	'Tipo' => "L",
			 	'target'=> "blank",
			 	'iconClass' => "fa-dashboard",
			 	"hasIcon" => true
			 	 ),

			 array('id' => 1 ,
			 	'label' => Text::_("Programación") ,
			 	'link' => "#",
			 	'Tipo' => "L",			 	
			 	'target'=> "blank",
			 	'iconClass' => "fa-dashboard",
			 	"hasIcon" => true

			 	 ),

			 array('id' => 2 ,
			 	'label' => Text::_("Formularios") ,
			 	'link' => "#",
			 	'Tipo' => "L",
			 	'iconClass'=> "fa-edit",
			 	"hasIcon" => true
			 	 ) ,

			array('id' => 3 ,
			 	'label' => Text::_("Graficos") ,
			 	'link' => "#",
			 	'Tipo' => "L",
			 	"iconClass"=> "fa-bar-chart-o",
			 	"hasIcon" => true
			 	 )
			  );

			return $this->listaMenu; 
        }

}

