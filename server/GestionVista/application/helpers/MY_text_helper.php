<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Text
{

	private $numerosBasicos=array('0'=>'cero','1'=>'un', '2'=>'dos','3'=>'tres','4'=>'cuatro',
                        '5'=>'cinco','6'=>'seis','7'=>'siete','8'=>'ocho','9'=>'nueve');
	 
	private $decenas=array('10'=>'diez','11'=>'once','12'=>'doce','13'=>'trece','14'=>'catorce',
                        '15'=>'quince','16'=>'dieciseis','17'=>'diecisiete','18'=>'dieciocho', 
                        '19'=>'diecinueve'); 

	private  $masDecenas=array('20'=>'veinte','21'=>'veintiun','22'=>'veintidos','23'=>'veintitres',
                        '24'=>'veinticuatro','25'=>'veinticinco','26'=>'veintiseis','27'=>'veintisiete', 
                        '28'=>'veintiocho','29'=>'veintinueve','30'=>'treinta','40'=>'cuarenta', 
                        '50'=>'cincuenta','60'=>'sesenta','70'=>'setenta','80'=>'ochenta', 
                        '90'=>'noventa'); 

	private  $centenas=array('100'=>'cien','200'=>'doscientos','300'=>'trescientos',
                        '400'=>'cuatrocientos','500'=>'quinientos','600'=>'seiscientos', 
                        '700'=>'setecientos','800'=>'ochocientos','900'=>'novecientos');

	protected static $_vars = array();						

	public static function _($params = 'null')
	{
		
		 
		if(is_object($params)){
			return $params;
		}
		
		$params = trim($params);
		
		$array = self::leerIdioma( "ES" );
	
		if(array_key_exists($params, $array)){
		
			if(!self::isUTF8($array[$params])){
				$array[$params] = utf8_encode($array[$params]);
			}
			return $array[$params];
		}
		
		if(!self::isUTF8($params)){
			$params = utf8_encode($params);
		}
		 
		return $params;
	}


	public static function renderOptions($select, $lisObj, $print = true){	

		$selectRs = $select; 
		$vOption = '<option value=""> --- </option>'; 
		
			foreach ($lisObj as $key => $value) {
				$vOption .= '
				<option value="'. $value. '">
                                '. $key. '
                            </option>'; 			
			}

		$selectRs .= $vOption . "</select>"; 

		if($print){
			echo $selectRs; 
		}
		return $selectRs;
	}

	public static function renderOptionsKeyVal($select, $lisObj, $print = true){	

		$selectRs = $select; 
		$vOption = '<option value=""> --- </option>'; 
		
			foreach ($lisObj as $key => $value) {
				$vOption .= '
				<option value="'. $key. '">
                                '. $value. '
                            </option>'; 			
                            
			}

		$selectRs .= $vOption . "</select>"; 

		if($print){
			echo $selectRs; 
		}
		return $selectRs;
	}
	
	public static function leerIdioma($file, $force=false){		
		$file = strtolower($file);	
		
		if (!file_exists($file = APPPATH . 'language/' . $file . ".ini") )
        {	
			$file = 'es';
		}
		
		
		if(isset(self::$_vars[$file]) && !$force) {
			return self::$_vars[$file];
		}
		
        self::$_vars[$file] = parse_ini_file($file, true);
		 
		return self::$_vars[$file];	
	}

	public static function _decodeUTF8($params){
		if(self::isUTF8($params)){
			$params = utf8_decode($params);
		}
		return $params;
	}

	/**
	 *
	 * Conveirte una cadena en Mayuscula filtrandolo por UTF-8.
	 * @param String $string
	 */
	public static function strtoupper($string){
		if(self::isUTF8($string)){
			$string = utf8_decode($string);
		}
		$string = self::_(strtoupper($string));
		return $string;
	}

	/**
	 *
	 * conveirte una cadena en miniscula filtrandolo por UTF-8
	 * @param String $string
	 */
	public static function strtolower($string){
		if(self::isUTF8($string)){
			$string = utf8_decode($string);
		}
		$string = self::_(strtolower($string));
		return $string;
	}

	public static function primeraMayuscula($string){
		if(self::isUTF8($string)){
			$string = utf8_decode($string);
		}
		$string = ucwords(self::_(strtolower($string)));
		return $string;
	}
	
	public static function primeraMayusculaPalabraFrase($string = '', $longitudMinimo = 0,  $longitudMinimoInicio = 0) {
		$stringNuevo = '';
		$parametroString = explode(" ", $string);
		$primera = true;
		$ultimo = "";
		$alterno = false;
		foreach ($parametroString as $parteString) {
			if ($longitudMinimo != 0) {
				if (strlen($ultimo) != 0) {
					if ($ultimo[strlen($ultimo)-1] == ",") {
						$alterno = true;
					}
				}
				if (trim($parteString) != 'New' && !$alterno) {
					if ((strlen($parteString) < $longitudMinimo) && !$primera) {
						$stringNuevo .= Text::strtolower($parteString)." ";
					} else {
						$stringNuevo .= Text::primeraMayuscula($parteString)." ";
					}
				} else {
					$stringNuevo .= Text::primeraMayuscula($parteString)." ";
					$alterno = false;
				}
			} else {
				$stringNuevo .= Text::primeraMayuscula($parteString)." ";
			}
			$ultimo = $parteString;
			$primera = false;
		}
		return trim($stringNuevo);
	}
	
	public static function primeraMayusculaPalabraFraseJunta($string = '') {
		$stringNuevo = '';
		$parametroString = explode(" ", $string);
		foreach ($parametroString as $parteString) {
			$stringNuevo .= Text::primeraMayuscula($parteString);
		}
		return trim($stringNuevo);
	}
	
	/**
	 *
	 * confirma si una cadena contiene caracteres UTF-8.
	 * @param String $string
	 */
	public static function isUTF8($string)
	{
		for ($idx = 0, $strlen = strlen($string); $idx < $strlen; $idx++)
		{
			$byte = ord($string[$idx]);

			if ($byte & 0x80){
				if (($byte & 0xE0) == 0xC0)
				{
					// 2 byte char
					$bytes_remaining = 1;
				}
				else if (($byte & 0xF0) == 0xE0)
				{
					// 3 byte char
					$bytes_remaining = 2;
				}
				else if (($byte & 0xF8) == 0xF0){
					// 4 byte char
					$bytes_remaining = 3;
				}
				else{
					return false;
				}

				if ($idx + $bytes_remaining >= $strlen) {
					return false;
				}

				while ($bytes_remaining--){
					if ((ord($string[++$idx]) & 0xC0) != 0x80)
					{
						return false;
					}
				}
			}
		}
		return true;
	}

	/**
	 *
	 * Enter description here ...
	 * @param string $string
	 * @param string $formato: Para devolver la fecha deseada.
	 * @return $fecha:
	 * defaul : str
	 * 	$formato:									 |
	 *  str = string formato date YY-MM-DD H:i:s.	 |
	 *  unix = formato Unix.							 |
	 *  dt = Objecto DateTime						 |
	 *  c = string ISO 8601 date.					 |
	 *  y-m-d = string en formato YY-MM-DD			 |
	 *
	 */
	public static function setFormatoFecha($string, $formato = 'str'){
		if (trim($string) != "") {
			$fecha=  strtotime($string);
			$fechaFormato = date('c', $fecha);
			switch ($formato){
				case 'str':
					return date('Y-m-d H:i:s', $fecha);
					break;

				case 's':
					return $fechaFormato;
					break;

				case 'unix' :
					return $fecha;
					break;
				case 'dt':
					try{
						$dateTime = new  DateTime($fechaFormato);
					} catch (Exception $e) {
						echo $e->getMessage();
						exit(1);
					}
					return $dateTime;
					break;
				case 'y-m-d':
					return date('Y-m-d', $fecha);
					break;
				default:
					return date($formato, $fecha);
					break;
			}
		} else {
			return "";
		}
	}


	/**
	 *
	 * Agrega comas al numeros y cifras.
	 * @param unknown_type $numero
	 * @param unknown_type $moneda
	 */
	public static function setFormatoMoneda2($numero, $moneda){
		$longitud = strlen($numero);
		$punto = substr($numero, -1,1);
		$punto2 = substr($numero, 0,1);
		$separador = ".";
		if($punto == "."){
			$numero = substr($numero, 0,$longitud-1);
			$longitud = strlen($numero);
		}
		if($punto2 == "."){
			$numero = "0".$numero;
			$longitud = strlen($numero);
		}
		$num_entero = strpos ($numero, $separador);
		$centavos = substr ($numero, ($num_entero));
		$l_cent = strlen($centavos);
		if($l_cent == 2){$centavos = $centavos."0";}
		elseif($l_cent == 3){$centavos = $centavos;}
		elseif($l_cent > 3){$centavos = substr($centavos, 0,3);}
		$entero = substr($numero, -$longitud,$longitud-$l_cent);
		if(!$num_entero){
			$num_entero = $longitud;
			$centavos = ".00";
			$entero = substr($numero, -$longitud,$longitud);
		}
		$final = 0;
		$sep = '';
		$start = floor($num_entero/3);
		$res = $num_entero-($start*3);
		if($res == 0){$coma = $start-1; $init = 0;}else{$coma = $start; $init = 3-$res;}
		$d= $init; $i = 0; $c = $coma;
		while($i <= $num_entero){

			if($d == 3 && $c > 0){$d = 0; $sep = ","; $c = $c-1;}else{$sep = "";}
			$final .=  $sep.$entero[$i];
			$i = $i+1; // todos los digitos
			$d = $d+1; // poner las comas
		}
		if($moneda == "RD")  {$moneda = '$RD';
		return $moneda." ".$final.$centavos;
		}
		else if($moneda == "USD"){$moneda = '$USD';
		return $moneda." ".$final.$centavos;
		}
		else if($moneda == "euros")  {$moneda = '$EUR';
		return $final.$centavos." ".$moneda;
		}
	}
		
	public static function setFormatoMoneda($number, $fractional=false) {
		if ($fractional) {
			$number = sprintf('%.2f', $number);
		}
		while (true) {
			$replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
			if ($replaced != $number) {
				$number = $replaced;
			} else {
				break;
			}
		}
		return $number;
	}
		
	public static function diferenciaFecha(DateTime $fechaInicio, DateTime $fechaFin) {
		//defino fecha 1
		$ano1 = $fechaInicio->format("Y");
		$mes1 = $fechaInicio->format("m");
		$dia1 = $fechaInicio->format("d");
			
		//defino fecha 2
		$ano2 = $fechaFin->format("Y");
		$mes2 = $fechaFin->format("m");
		$dia2 = $fechaFin->format("d");
			
		//calculo timestam de las dos fechas
		$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
		$timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2);
			
		//resto a una fecha la otra
		$segundosDiferencia = $timestamp1 - $timestamp2;
		//echo $segundos_diferencia;
			
		//convierto segundos en días
		$diasDiferencia = $segundosDiferencia / (60 * 60 * 24);
			
		//obtengo el valor absoulto de los días (quito el posible signo negativo)
		$diasDiferencia = abs($diasDiferencia);
			
		//quito los decimales a los días de diferencia
		$diasDiferencia = floor($diasDiferencia);
			
		return $diasDiferencia;
	}


	//letras en numeros.

	static function analizadorMonto($montoNumerico){
		 
		$tempString=strval($montoNumerico);
		$array=explode('.',$tempString);    //si encuentra un punto separa....
		$lenghtEnteros=strlen($array[0]);
		$montoLetra = '';
		 
		$textClass = new Text();

		if(sizeof($array)>1)  //encontro punto...tendra pesos y centavos
		$punto=true;
		else $punto=false;        //solo tendra pesos o centavos
		if ($punto){    //si tiene fracciones
			//-
			$fraccion=substr($array[1],0,2);

			//-si la longitud es menor a 2
			if (strlen($fraccion)<2)
			$fraccion.='0';

			$fraccion.='/100 M. N.';
		}else $fraccion='00/100 M. N.';

		if ($lenghtEnteros>3){    //decenas y centenas
			//echo 'hol';
			$flagLongitud=true;
			$cadenaEntera=$array[0];    //cadena completa
			$count=0;
			$enteros='';
			do {
				$posfijo='';

				if (strlen($cadenaEntera)>=3)
				$fin=substr($cadenaEntera,-3);    //final de la cadena
				else $fin=$cadenaEntera;    //final de la cadena

				if (strlen($fin)<3){
					while (strlen($fin)!=3){
						$fin='0'.$fin;
					}

					$flagLongitud=false;
				}else $cadenaEntera=substr($cadenaEntera,0,-3);    //principio de la cadena
				$cantidadTemp=$textClass->makeMonto3cifras($fin);

				if(pow(-1,$count)==-1){
					$posfijo='mil';
				}elseif ($count) {
					if (strlen($cadenaEntera)>1 || substr($cadenaEntera,-1)!='1')
					$posfijo='millones';
					else $posfijo='millon';
				}

				if ( $cantidadTemp=='un' && $posfijo=='mil'){
					$enteros=$posfijo.' '.$enteros;
				}elseif ($cantidadTemp!=''){
					$enteros=$cantidadTemp.' '.$posfijo.' '.$enteros;
				}

				$count++;    //cuantas veces he pasado por el ciclo

			} while ($flagLongitud);

			$montoLetra =$enteros.' ';

		}else {
			$dif=3-$lenghtEnteros;
			$i=0;
			$enteros=$array[0];
			while ($i<$dif){
				$enteros='0'.$enteros;
				$i++;
			}
			$monto3cifras=$textClass->makeMonto3cifras($enteros);
			if ($monto3cifras!='')
			$montoLetra=$monto3cifras.' ';
			else $montoLetra= $textClass->numerosBasicos['0'].' ';
		}
		//= preg_replace('/\s+/', ' ', $text);
		// echo $this->montoLetra=$this->montoLetra.' '.$fraccion;
		//return $montoLetra=preg_replace('/\s+/', ' ',$montoLetra.' '.$fraccion);

		$montoLetra=preg_replace('/\s+/', ' ',$montoLetra);
		return $montoLetra;
	}

	function makeMonto3cifras($monto){
		$string='';
		$substring='';
		if (array_key_exists($monto,$this->centenas)) {    //100, 200 o mas
			$string=$this->centenas[$monto];
		}else {
			if ($monto{0}!='0' && $monto{0}!='1'){
				$string=$this->centenas[$monto{0}.'00'];
			}elseif ($monto{0}==1) $string='ciento';
			$decenas=substr($monto,1,2);
			if (array_key_exists($decenas,$this->decenas)){
				$string.=' '.$this->decenas[$decenas];
			}elseif (array_key_exists($decenas,$this->masDecenas)){
				$string.=' '.$this->masDecenas[$decenas];
			}else {//si no se ajusto a ningun caso especial
				if ($monto{1}=='0'){
					if ($monto{2}!='0')
					$substring.=$this->numerosBasicos[$monto{2}];

				}else {
					if (array_key_exists($monto{1}.'0',$this->masDecenas)){
						$substring=$this->masDecenas[$monto{1}.'0'];
						if ($monto{2}!='0')
						$substring.=' y '.$this->numerosBasicos[$monto{2}];
					}
				}
			}
		}
		if ($substring!='' && $string!='')    //si substring tiene algo la concateno
		$string.=' '.$substring;
		elseif ($substring!='')
		$string.=$substring;
		return $string;
	}
	 /**
	  * 
	  * Devuelve true si la fecha as Mayor que Fecha 2
	  * 
	  */
	static function compararFecha($fecha, $fecha2) {
		$xMonth = substr($fecha, 5, 2);   //$fecha.substring(5, 7);
		$xDay= substr($fecha, 8, 2); //$fecha.substring(8, 10);
		$xYear= substr($fecha, 0, 4); //$fecha.substring(0,4);
		
		$yMonth= substr($fecha2, 5, 2); //$fecha2.substring(5, 7);
		$yDay= substr($fecha2, 8, 2); //$fecha2.substring(8, 10);
		$yYear= substr($fecha2, 0, 4); //$fecha2.substring(0,4);
		if ($xYear > $yYear)  {
			return true;
		}  else  {
			if ($xYear == $yYear)  {
				if ($xMonth > $yMonth)  {
					return true ;
				} else {
					if ($xMonth == $yMonth)  {
						if ($xDay > $yDay) {
							return true ;
						} else {
							return false ;
						}
					} else {
						return false ;
					}
				}
			} else {
				return false;
			}
		}
	}
	
	/**
	 *
	 * Devuelve true si la fecha as Mayor o igual que Fecha 2
	 *
	 */
	static function compararFechaMayorIgual($fecha, $fecha2) {
		$xMonth = substr($fecha, 5, 2);   //$fecha.substring(5, 7);
		$xDay= substr($fecha, 8, 2); //$fecha.substring(8, 10);
		$xYear= substr($fecha, 0, 4); //$fecha.substring(0,4);
	
		$yMonth= substr($fecha2, 5, 2); //$fecha2.substring(5, 7);
		$yDay= substr($fecha2, 8, 2); //$fecha2.substring(8, 10);
		$yYear= substr($fecha2, 0, 4); //$fecha2.substring(0,4);
		if ($xYear > $yYear)  {
			return true;
		}  else  {
			if ($xYear == $yYear)  {
				if ($xMonth > $yMonth)  {
					return true ;
				} else {
					if ($xMonth == $yMonth)  {
						if ($xDay > $yDay || $xDay == $yDay) {
							return true ;
						} else {
							return false ;
						}
					} else {
						return false ;
					}
				}
			} else {
				return false;
			}
		}
	}

	public static function diferenciaEntreFechas($fecha1, $fecha2){
		$fechaInicio= $fecha1;
		$fechaFin= $fecha2;
		$s = strtotime($fechaFin)-strtotime($fechaInicio);
		$d = intval($s/86400);
		$s -= $d*86400;
		$h = intval($s/3600);
		$s -= $h*3600;
		$m = intval($s/60);
		$s -= $m*60;
		$mes = $d/30;
		$trimestre = $d/90;
		$cuatrimestre = $d/120;
		$anios = $d/365;
		$diferencia['segundos'] = ($s);
		$diferencia['minutos'] = ($m);
		$diferencia['horas'] = (($d*24)+$h);
		$diferencia['dias'] = $d;
		$diferencia['mes'] = floor($mes);
		$diferencia['trimestre'] = floor($trimestre);
		$diferencia['cuatrimestre'] = floor($cuatrimestre);
		$diferencia['anios'] = floor($anios);
		return $diferencia;
	}

/**
 * 
 * @descripcion evueve true si la Hora1 es mayor que la Hora Dos.
 * @param string $hora
 * @param String $hora2
 */
	static function compararHora($hora, $hora2) {
		$xHora = substr($hora, 0, 2);
		$xMinuto = substr($hora, 3, 6);
		$yHora = substr($hora2, 0, 2);
		$yMinuto = substr($hora2, 3, 6);
		if ($xHora > $yHora) {
			return true;
		} else {
			if ($xHora == $yHora) {
				if ($xMinuto > $yMinuto) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
	}

	static function cantidadDeTiempo($hora, $hora2) {
		$xHora = substr($hora, 0, 2);
		$xMinuto = substr($hora, 3, 6);
		$yHora = substr($hora2, 0, 2);
		$yMinuto = substr($hora2, 3, 6);
		$hora  = abs($yHora - $xHora);
		$minuto = $yMinuto - $xMinuto;
		if ($minuto < 0) {
			$hora--;
		}
		$minuto = abs(($minuto/60));
		if ($minuto != 0) {
			$minuto = explode(".", $minuto);
			$minuto = $minuto[1];
		}
		return $hora.":".$minuto;
	}
	
	static function cantidadDeTiempo2($hora, $hora2) {
		$xHora = substr($hora, 0, 2);
		$xMinuto = substr($hora, 3, 6);
		$yHora = substr($hora2, 0, 2);
		$yMinuto = substr($hora2, 3, 6);
		$hora  = abs($yHora - $xHora);
		$minuto = $yMinuto - $xMinuto;
		if ($minuto < 0) {
			$hora--;
		}
		$minuto = abs(($minuto/60));
		if ($minuto != 0) {
			$minuto = explode(".", $minuto);
			$minuto = $minuto[1];
		}
		$minuto = $minuto / 100;
		return $hora.".".$minuto;
	}

	static function diaDeLaSemana($fecha) {
		$fechaConvertida = strtotime(Text::setFormatoFecha($fecha, "Y/m/d"));
		$diaRecorrido = getdate(mktime(0, 0, 0, date('m', $fechaConvertida), date('d', $fechaConvertida), date('Y', $fechaConvertida)));
		$dia='';
		$arreglo = jddayofweek ( cal_to_jd(CAL_GREGORIAN, $diaRecorrido['mon'],$diaRecorrido['mday'], $diaRecorrido['year']) , 0);
		switch ($arreglo) {
			case 0:
				$dia = 'do';
				break;
			case 1:
				$dia = 'lu';
				break;
			case 2:
				$dia = 'ma';
				break;
			case 3:
				$dia = 'mi';
				break;
			case 4:
				$dia = 'ju';
				break;
			case 5:
				$dia = 'vi';
				break;
			case 6:
				$dia = 'sa';
				break;
		}
		return $dia;
	}
	
	static function obtenerDiaLetra($diaString = '') {
		switch (strtolower($diaString)) {
			case 'do':
				$dia = 'D';
				break;
			case 'lu':
				$dia = 'L';
				break;
			case 'ma':
				$dia = 'K';
				break;
			case 'mi':
				$dia = 'M';
				break;
			case 'ju':
				$dia = 'J';
				break;
			case 'vi':
				$dia = 'V';
				break;
			case 'sa':
				$dia = 'S';
				break;
		}
		return $dia;
	}



	static function _clean_input_keys($str = '') {
		//```````````````$a = array("á", "Ã¡");
		//	$e = array("é", "Ã©");
		//	$i = array("í", "Ã-");
		//	$o = array("ó", "Ã³");
		//	$u = array("ú", "Ãº");
		//	$n = array("ñ", "Ã±");
		$cadenaValida = array("R", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "%", "!", "@", "#", "$", "^", "&", "*","~", "+", ":", "-", "=", "?", "'", ".", "<", "Ã", "±");
		$parametro = str_replace(".", "", $str);
		$parametro = str_replace(" ", "", $parametro);
		$parametro = str_replace("±", "", $parametro);
		$parametro = str_replace("Ã", "", $parametro);
		$parametro = str_replace("@", "", $parametro);
		$parametro = str_replace("1", "", $parametro);
		$parametro = str_replace("2", "", $parametro);
		$parametro = str_replace("3", "", $parametro);
		$parametro = str_replace("6", "", $parametro);
		$parametro = str_replace("0", "", $parametro);
		$parametro = str_replace("controlseguridadphp", "ad", $parametro);
		$parametro = str_replace("buscadorphp", "a", $parametro);
		$parametro = str_replace("buscadorphp", "a", $parametro);
		
		
		$parametro = str_ireplace($cadenaValida, "", $str);
		echo $parametro;
		if (strlen(trim($parametro)) == 0) {
			echo $parametro;
			return $str;
		} else {
			echo $parametro;
			echo strlen($parametro);
			exit('Disallowed Key Characters.');
		}
		/*
		if (strlen($parametro) != 0) {
			$regex = "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
			if (!preg_match("/^$regex$/", $parametro)) { 
				echo "<br/>";
				echo $parametro."--in";
				echo "<br/>";
				//return $str;
				exit('Disallowed Key Characters.');
			} else {
				return $str;
			}
		}
		*/
	}
	
	function secureSuperGlobalGET(&$value, $key) {
        $_GET[$key] = htmlspecialchars(stripslashes($_GET[$key]));
        $_GET[$key] = str_ireplace("script", "blocked", $_GET[$key]);
        $_GET[$key] = mysql_escape_string($_GET[$key]);
        return $_GET[$key];
    }
    
    function secureSuperGlobalPOST(&$value, $key) {
        $_POST[$key] = htmlspecialchars(stripslashes($_POST[$key]));
        $_POST[$key] = str_ireplace("script", "blocked", $_POST[$key]);
        $_POST[$key] = mysql_escape_string($_POST[$key]);
        return $_POST[$key];
    }
        
    function secureGlobals()
    {
        array_walk($_GET, array($this, 'secureSuperGlobalGET'));
        array_walk($_POST, array($this, 'secureSuperGlobalPOST'));
    }
	
	
	

	static function decimal_romano($numero)
	{
		$var= "";
		$numero=floor($numero);
		if($numero<0)
		{
			$var= "-";
			$numero=abs($numero);
		}
		# Definición de arrays
		$numerosromanos=array(1000,500,100,50,10,5,1);
		$numeroletrasromanas=array("M"=>1000,"D"=>500,"C"=>100,"L"=>
		50,"X"=>10,"V"=>5,"I"=>1);
		$letrasromanas=array_keys($numeroletrasromanas);

		while($numero)
		{
			for($pos=0;$pos<=6;$pos++)
			{
				$dividendo=$numero/$numerosromanos[$pos];
				if($dividendo>=1)
				{
					$var.=str_repeat($letrasromanas[$pos],floor($dividendo));
					$numero-=floor($dividendo)*$numerosromanos[$pos];
				}
			}
		}
		$numcambios=1;
		$parcial = '';
		$parcialfinal = '';
		while($numcambios)
		{
			$numcambios=0;
			for($inicio=0;$inicio<strlen($var);$inicio++)
			{
				$parcial=substr($var,$inicio,1);
				if($parcial==$parcialfinal&&$parcial!="M")
				{
					$apariencia++;
				}else{
					$parcialfinal=$parcial;
					$apariencia=1;
				}
				# Caso en que encuentre cuatro carácteres seguidos iguales.
				if($apariencia==4)
				{
					$primeraletra=substr($var,$inicio-4,1);
					$letra=$parcial;
					$sum=$letra+$numero*4;
					$pos=self::busqueda($letra,$letrasromanas);
					if($letrasromanas[$pos-1]==$primeraletra)
					{
						$cadenaant=$primeraletra.str_repeat($letra,4);
						$cadenanueva=$letra.$letrasromanas[$pos-2];
					}else{
						$cadenaant=str_repeat($letra,4);
						$cadenanueva=$letra.$letrasromanas[$pos-1];
					}
					$numcambios++;
					$var=str_replace($cadenaant,$cadenanueva,$var);
				}
			}
		}
		return $var;
	}

	function busqueda($cadenanueva,$array)
	{
		$pos = 0;
		foreach($array as $contenido)
		{
			if($contenido==$cadenanueva)
			{
				return $pos;
			}
			$pos++;
		}
	}
	
	static function obtenerNombreDelMes($numeroMes){
			$nombreMes = '';
							switch ($numeroMes){
								case 1:
									$nombreMes = Text::_("Enero");								
									break;
								case 2:
									$nombreMes = Text::_("Febrero");									
									break; 
								case 3:
									$nombreMes = Text::_("Marzo");								
									break;
								case 4:
									
									$nombreMes =  Text::_("Abril");								
									break;
								case 5:
									$nombreMes =  Text::_("Mayo");									
									break;
								case 6:
									$nombreMes =  Text::_("Junio");								
									break;
								case 7:
									$nombreMes =  Text::_("Julio");								
									break;
								case 8:
									$nombreMes =  Text::_("Agosto");								
									break;
								case 9:
									$nombreMes =  Text::_("Septiembre");								
									break;								
								case 10:
									$nombreMes = Text::_("Octubre");							
									break;
								case 11:
									$nombreMes = Text::_("Noviembre");
									break;
								case 12:
									$nombreMes = Text::_("Diciembre");								
									break;								
							}
							
				return $nombreMes;		
	} 
	
	static function obtenerNombreDiaSemana($numeroDia){
			$nombreDia = '';
							switch ($numeroDia){
								case 1:
									$nombreDia = Text::_("Lunes");								
									break;
								case 2:
									$nombreDia = Text::_("Martes");									
									break; 
								case 3:
									$nombreDia = Text::_("Miércoles");								
									break;
								case 4:
									$nombreDia =  Text::_("Jueves");								
									break;
								case 5:
									$nombreDia =  Text::_("Viernes");									
									break;
								case 6:
									$nombreDia =  Text::_("Sábado");								
									break;
								case 7:
									$nombreDia =  Text::_("Domingo");								
									break;
							}
							
				return $nombreDia;		
	} 
		
	public static function quitarAcento($cadena = "") {
		$a = array("á", "Ã¡");
	//	$e = array("é", "Ã©");
	//	$i = array("í", "Ã-");
	//	$o = array("ó", "Ã³");
	//	$u = array("ú", "Ãº");
	//	$n = array("ñ", "Ã±");
		$a = array("á", "a");
		$e = array("é", "e");
		$i = array("í", "i");
		$o = array("ó", "o");
		$u = array("ú", "u");
		$n = array("ñ", "n");
	//	$a = array("Ã¡", "a");
	//	$e = array("Ã©", "e");
	//	$i = array("Ã-", "i");
	//	$i1 = array("Ã­", "i");
	//	$o = array("Ã³", "o");
	//	$u = array("Ãº", "u");
	//	$n = array("Ã±", "n");
		
		$listaLetra = array($a, $e, $i, $o, $u, $n);
		$cadenaNueva = $cadena;
		foreach ($listaLetra as $letra) {
			$caracter = $letra[0];
			$caracterReemplazo = $letra[1];
			$valor = strstr($cadena, Text::_($caracter));
			
			if ($valor) {
				$cadenaNueva = str_replace(Text::_($caracter), $caracterReemplazo, $cadena);
				$cadena = $cadenaNueva;
			}
		}
		return $cadenaNueva;
	}
	
	public static function setFormatoPorciento($valor = 0) {
		if ($valor >= 10) {
			return "0.".$valor;
		} else {
			return "0.0".$valor;
		}
	}
	
	public static function ultimoDiaMes($ano,$mes) {
		return date("d",(mktime(0,0,0,$mes+1,1,$ano)-1));
	}

	public static function calcularOffset($cantidad, $pagina){
		return $cantidad * $pagina;
	}
}

Class Encryption {

	public static function decode($string){
	
	return $string;
	}
	
	public static function encode($string){
	
		return $string;
	
	}
}


?>