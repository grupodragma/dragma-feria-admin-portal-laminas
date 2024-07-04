<?php

namespace Application\Helper;

use Laminas\Validator\Date;

class Tools {
    
    private $serviceManager;
    
    public function __construct($serviceManager) {
        $this->serviceManager = $serviceManager;
    }

	public function toAscii($str, $delimiter='-') {
		$str = trim($str);
		$str = str_replace(["Ñ","ñ"],["N","n"],$str);
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]\?/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
		$clean = str_replace(["&","'","*",'"','#','?','(',')',',','!','`',','],"",$clean);
		return $clean;
	}
	
	public function validateFormatDate($format){
		return new Date(['format' => (string)$format]);
	}

	public function validarNumeroRangoPrecio($min, $max){
		if((double)$min <= (double)$max){
			return true;
		}
	}

	public function timeFormat($time){
        return ( $time != '') ? $time : '00:00';
    }

}