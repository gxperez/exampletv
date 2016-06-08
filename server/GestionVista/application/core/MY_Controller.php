<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


class MY_Controller extends CI_Controller {

	public $id;   
    public $tema;
	public $vista = null;
	private $es_ajax = false;
	
  public function __construct()
    {
	parent::__construct();
	
	// Declarar las Constantes
	$this->setConstantes(); 
	
    }

	public function setConstantes(){
	
		$this->load->helper('url');
	
		if (!$this->session->userdata('GETLANGUAGE')){
			$this->session->set_userdata('GETLANGUAGE', 'es'); 
			$this->GETLANGUAGE ='es';
			
		} else {
			$this->GETLANGUAGE = $this->session->userdata('GETLANGUAGE');
		}
	}	

		
	public function usuarioPermiso($usuarioRol =array()) {	

		if (!in_array($this->session->userdata("sRol"), $usuarioRol) ) {
		
		return false;
		
		} else {
			return true; 
		}	
	}

}