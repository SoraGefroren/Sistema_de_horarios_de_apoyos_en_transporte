<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Se define clase de modelo
class Fncs_mdl extends CI_Model {

	// Función para hashear un valor string
	public function get_hash ($str) {
		$valhash = password_hash($str, PASSWORD_BCRYPT);
		return $valhash;
	}

	// Función para verificar un valor string hasheado
	public function verify_hash ($str, $valhash) {
		$rhash = password_verify($str, $valhash);
		return $rhash;
	}

	// Función para validar el formato de un string de hora
	public function validateTime($hms) {
	    if(preg_match("/^([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])[\:]([0-5][0-9])$/",$hms)) {
	        return true;
	    }
	    return false;
	}

	// Función para validar el formato de un string númerico
	public function validateNum($str) {
	    if(preg_match("/^[0-9]+$/",$str)) {
	        return true;
	    }
	    return false;
	}

	// Función para validar el formato de un string email
	public function validateEmail($str) {
	    if(1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{1,6})$/', $str)) {
	        return true;
	    }
	    return false;
	}

	// Función para validar el formato de un string telefono
	public function validateTel($str) {
	    if(preg_match("/^[0-9\s]+$/",$str)) {
	        return true;
	    }
	    return false;
	}

	// Función para validar el formato de un string constraseña
	public function validatePass($str) {
	    if(!preg_match("/([a-z])+/",$str)) {
	        return false;
	    }
	    if(!preg_match("/([A-Z])+/",$str)) {
	        return false;
	    }
	    if(!preg_match("/([0-9])+/",$str)) {
	        return false;
	    }
	    if(!preg_match("/^([a-zA-Z0-9])+$/",$str)) {
	        return false;
	    }
	    return true;
	}

	// Obtener IP cliente
	public function getip() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
		return $ip;
	}
}

?>
