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

	public function generateGUID($namespace= "cama"){	

    static $guid = '';
    $uid = uniqid("", true);
    $data = $namespace;
    $data .= $_SERVER['REQUEST_TIME'];
    $data .= $_SERVER['HTTP_USER_AGENT'];
    $data .= microtime();
    $data .= mt_rand(100, 10000);
    $data .= $_SERVER['REMOTE_ADDR'];
    $data .= $_SERVER['REMOTE_PORT'];
    $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
    $guid = '' .  
            substr($hash,  0,  8) .
            '-' .
            substr($hash,  8,  4) .
            '-' .
            substr($hash, 12,  4) .
            '-' .
            substr($hash, 16,  4) .
            '-' .
            substr($hash, 20, 12) .
            '';
    return  trim($guid);

	}

}