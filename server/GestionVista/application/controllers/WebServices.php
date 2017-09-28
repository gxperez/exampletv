<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class WebServices extends MY_Controller {

	/**
	 * Index Page for this controller.
	 	@autor:	Grequis Xavier Perez Fortuna.
	 */
	public function index()
	{			
	}


	public function pushMsgFVs(){
		// Validacion del Has
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

}


?>