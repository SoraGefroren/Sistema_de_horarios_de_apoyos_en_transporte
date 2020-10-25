<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Se define clase de apoyo
class Apoyo_en_transporte extends CI_Controller {
	// -------------------------------------------------
	// CONSTRUCTOR
	function __construct()
	{
		parent:: __construct();
	}
	// -------------------------------------------------
	// VARIABLES
	private $porRango = 'En un rango de días';
	private $porDia = 'Solo para un día';
	// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
	// CARGA DE PÁGINAS
	// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	// -------------------------------------------------
	// Cargar página PRINCIPAL
	public function index()
	{
		// Se carga libreria de sesiones
		$this->load->library('session');
		// Cargar página principal
		$this->load->view('/_parts/_1_top', array(
			'nombre_pag' => 'AET'
		));
		$this->load->view('/_parts/nav', array(
			'titulo' => 'Apoyo en transporte (Xalapa, Ver, México)',
			'pag_name' => 'index'
		));
		$this->load->view('main', array(
			'titulo' => 'Apoyo en transporte'
		));
		$this->load->view('/_parts/_2_bot', array(
			'pag_name' => 'index'
		));
	}
	// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
	// FUNCIONALIDAD PRIVADA
	// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	// -------------------------------------------------
	// Función para salvar la acción de un usuario
	private function salvarAccion ($tipo, $extr)
	{
		// Accion
		$accion = '';
		// [0] => Acción
		// Seleccionar acción
		switch ($tipo) {
			case 1:
				$accion = '[Obtener JSON Público]|';
				break;
			case 2:
				$accion = '[Obtener JSON Privado]|';
				break;
			case 3:
				$accion = '[Iniciar sesión]|';
				break;
			case 4:
				$accion = '[Cerrar sesión]|';
				break;
			case 5:
				$accion = '[Obtener JSON Usrs]|';
				break;
			case 6:
				$accion = '[Obtener JSON Patrones]|';
				break;
			default:
				break;
		}
		// [1] => Origen
		// Origen de la acción
		$accion .= "[" . $this->fncs_mdl->getip() . "]|";
		// [2...] => Datos extras
		// Concatenar cadena extra
		if ($extr && !empty($extr)) {
			$accion .= $extr;
		}
		// Recuperar usuario
		$usrNum = $this->session->userdata('s_usr');
		if (!$usrNum || empty($usrNum)) {
      		$usrNum = null;
		}
		// Conectar base de datos
		$dbtecint = $this->load->database('tecint', true);
		// Limpiar número de usuario
		if ($usrNum && !empty($usrNum)) {
			$usrNum = $dbtecint->escape_str($usrNum);
		}
		// Limpiar acción
		$accion = $dbtecint->escape_str($accion);
		// Insertar
		$dbtecint->insert('accion_usr', array (
			'reg_usr' => $usrNum,
			'fecha' => date('Y-m-d'),
			'hora' => date('H:i:s'),
			'resumen' => $accion,
		));
		// Cerrar conexión a db
		$dbtecint->close();
	}
	// -------------------------------------------------
	// Función para salvar los cambios que realice un usuario
	private function registrarCambio ($tipo, $reg, $cadena, $adicional)
	{
		// Valido y ajustar cadena
		if (!$cadena || empty($cadena)) {
			$cadena = '';
		}
		// Valido y ajustar registro
		if (!$reg || empty($reg)) {
			$reg = null;
		}
		// Se asigna origen de la acción a una cadena
		$cadena = "[" . $this->fncs_mdl->getip() . "]" . $cadena;
		// Valido y ajustar cadena adicional
		if (!$adicional || empty($adicional)) {
			$adicional = '';
		}
		// Obtener usuario
		$usrNum = $this->session->userdata('s_usr');
		if (!$usrNum || empty($usrNum)) {
      		$usrNum = null;
		}
		// Conectar base de datos
		$dbtecint = $this->load->database('tecint', true);
		// Ajustar número de usuario
		if ($usrNum && !empty($usrNum)) {
			$usrNum = $dbtecint->escape_str($usrNum);
		}
		// Ajustar número de registro
		if ($reg && !empty($reg)) {
			$reg = $dbtecint->escape_str($reg);
		}
		// Ajustar cadena y valor adicional
		$cadena = $dbtecint->escape_str($cadena);
		$adicional = $dbtecint->escape_str($adicional);
		// Seleccionar acción
		switch ($tipo) {
			case 1:
				// Insertar
				$dbtecint->insert('act_apy_transport', array (
					'reg_usr' => $usrNum,
					'reg_apy' => $reg,
					'fecha' => date('Y-m-d'),
					'hora' => date('H:i:s'),
					'resumen' => $cadena,
					'adicional' => $adicional,
				));
				break;
			case 2:
				// Insertar
				$dbtecint->insert('act_usr', array (
					'reg_usr' => $usrNum,
					'reg_afect' => $reg,
					'fecha' => date('Y-m-d'),
					'hora' => date('H:i:s'),
					'resumen' => $cadena,
					'adicional' => $adicional,
				));
				break;
			case 3:
				// Insertar
				$dbtecint->insert('act_patron', array (
					'reg_usr' => $usrNum,
					'reg_afect' => $reg,
					'fecha' => date('Y-m-d'),
					'hora' => date('H:i:s'),
					'resumen' => $cadena,
					'adicional' => $adicional,
				));
				break;
		}
		// Cerrar conexión a db
		$dbtecint->close();
	}
	// -------------------------------------------------
	// Función para validar al usuario y su tipo
	private function validarUsrTipo ($sse, $stp)
	{
		// Conectar base de datos
		$dbtecint = $this->load->database('tecint', true);
		// Ajuste obtener el valor original de los parametros
		$sse = $dbtecint->escape_str($sse);
		$stp = $dbtecint->escape_str($stp);
		// Seleccionar datos de la BD
		$dbtecint->select('t_usr.reg, t_tus.tipo, t_usr.estado');
		$dbtecint->from('usr_sys AS t_usr');
		$dbtecint->join('tipo_usr AS t_tus', 't_usr.reg_tipo = t_tus.reg');
		$dbtecint->where('t_usr.reg =', $sse);
		$dbtecint->where('t_tus.tipo =', $stp);
		// Obtener resultado de selección
		$rQry = $dbtecint->get();
		// Cerrar conexión a db
		$dbtecint->close();

		// Bandera
		$isOk = TRUE;
		// Validar número de filas como respuesta
		if ($rQry->num_rows() === 1) {
			// Se toma la única fila que debiese existir
			$rw = $rQry->first_row();
			// Validar estado del registro
			if ($rw->estado) {
				$isOk = TRUE;	
			} else {
				$isOk = FALSE;
			}
		} else {
			$isOk = FALSE;
		}

		return $isOk;
	}
	// -------------------------------------------------
	// Función para validar los intentos de sesión
	private function validarIntentos () {
		/*
		// Cambiar zona horaria
		date_default_timezone_set("America/Mexico_City");
		// Se carga libreria de sesiones
		$this->load->library('session');
		// Cargar modelo de funciones
		$this->load->model('fncs_mdl');
		*/
		// Recupera fecha y hora actual
		$mntActual = date('Y-m-d H:i:s');
		$mntActual = strtotime ('-120 minute', strtotime ($mntActual));
		$mntActual = date('Y-m-d H:i:s', $mntActual);
		// Conectar base de datos
		$dbtecint = $this->load->database('tecint', true);
		// Seleccionar datos de la BD
		$dbtecint->select('TIMESTAMP(t_au.fecha, t_au.hora) as mntR');
		$dbtecint->from('accion_usr AS t_au');
		$dbtecint->where('TIMESTAMP(t_au.fecha, t_au.hora) >', $mntActual);
		$dbtecint->like('t_au.resumen', '[Iniciar sesión]|['.$this->fncs_mdl->getip().']|[Intento.u:', 'after');
		$dbtecint->order_by("mntR", "desc");
		// Obtener resultado de selección
		$rQry = $dbtecint->get();
		// Cerrar conexión a db
		$dbtecint->close();
		// Validar número de filas como respuesta
		if ($rQry->num_rows() > 4) {
			// Recupera fecha y hora actual
			$mntActual = date('Y-m-d H:i:s');
			// Variables de apoyo
			$contObj = 0;
			$contAux = 0;
			$mntR = null;
			$mntRel = null;
			$mntAct = null;
			$diferencia = null;
			$strAux = "";
			$diffAux = 0;
			$diezM = ((10 * 100)/60) * 0.01;
			// Recorrer datos
			foreach($rQry->result()  as $row) {
				// Obtener momento de BD
				$mntRel = $row->mntR;
				// Validar existencia de momento
				if ($mntRel && !empty($mntRel)) {
					// Validar formato de fecha
					try {
						// Si la fecha viene en el formato adecuado
						if (DateTime::createFromFormat('Y-m-d H:i:s', $mntRel)) {
							// Se formatea la fecha
							$mntR = date('Y-m-d H:i:s', strtotime($mntRel));
							$mntRel = date_create($mntRel);
							$mntAct = date_create($mntActual);
							// Obtener diferencia entre el momento de BD y el momento actual
							$diferencia = date_diff($mntRel, $mntAct);
							// Fusionar datos de diferencia
							$diffAux = $diferencia->h + ((($diferencia->i * 100)/60) * 0.01);
							// -----------------------------
							// print_r("[".$contObj."|".$mntR."-vs-".$mntActual."|".$diffAux."-vs-".$diezM."]\n");
							// Actualizar momento actual
							$mntActual = $mntR;
							// Validar diferencia
							if ($diffAux < $diezM) {
								// Incrementar contador objetivo
								$contObj += 1;
							}
						}
					} catch (Exception $e) {/*No deberia haber una excepción*/}
					// Incrementar contador de auxiliar
					$contAux += 1;
					// Validar conteo
					if (($contAux > 4)) {
						break;
					}
				}
			}
			// -----------------------------
			// print_r("[".$contObj."]\n");
			// Validar conteo
			if ($contObj > 4) {
				//echo ("NO>>>>>>>>>>\n");
				return false;
			} else {
				//echo ("SI>>>>>>>>>>\n");
				return true;
			}
		} else {
			//echo ("SI>>>>>>>>>>\n");
			return true;
		}
		/*
		// Devolver datos en formato JSON
		return ($this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(
				json_encode($rQry->result())
			));
		*/
	}
	// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
	// FUNCIONALIDAD PUBLICA
	// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	// -------------------------------------------------
	// Obtener "JSON publico" sobre el apoyo en transporte
	public function informacion ()
	{
		// Cambiar zona horaria
		date_default_timezone_set("America/Mexico_City");
		// Obtener el último segmento de la URL
		$fecha = $this->uri->segment(3);
		// Se carga libreria de sesiones
		$this->load->library('session');
		// Cargar modelo de funciones
		$this->load->model('fncs_mdl');
		// Conectar base de datos
		$dbtecint = $this->load->database('tecint', true);
		// Validar si existecia del segmento
		if ($fecha && !empty($fecha)) {
			// Limpiar de variable
			$fecha = $dbtecint->escape_str($fecha);
			// Validar formato de fecha
			try {
				// Si la fecha viene en el formato adecuado
				if (DateTime::createFromFormat('Y-m-d', $fecha)) {
					// Se formatea la fecha
					$fecha = date('Y-m-d', strtotime($fecha));
				} else {
					// Si no, se toma la fecha actual
					$fecha = date('Y-m-d');
				}
			} catch (Exception $e) {
				// Si no existe el segmento se toma la fecha actual
				$fecha = date('Y-m-d');
			}
		} else {
			// Si no existe el segmento se toma la fecha actual
			$fecha = date('Y-m-d');
		}
		// Seleccionar datos de la BD
		$dbtecint->select('t_ta.tipo, t_at.hay_apy, t_at.dia_ini, t_at.dia_fin, t_at.hr_ini, t_at.hr_fin, t_p.patron, t_p.direccion, t_p.tels');
		$dbtecint->from('apy_transport AS t_at');
		$dbtecint->join('tipo_apy AS t_ta', 't_at.reg_tipo = t_ta.reg');
		$dbtecint->join('patrocinador AS t_p', 't_at.reg_patron = t_p.reg');
		$dbtecint->where('t_at.dia_ini >=', $fecha);
		$dbtecint->or_where('t_at.dia_fin >=', $fecha);
		$dbtecint->order_by("t_at.dia_ini", "asc");
		// Obtener resultado de selección
		$miQuery = $dbtecint->get();
		// Cerrar conexión a db
		$dbtecint->close();
		// Se salva accion
		$this->salvarAccion(1, '[Fecha:' . $fecha . ']');
		// Devolver datos en formato JSON
		return ($this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(
				json_encode($miQuery->result())
			));
	}
	// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
	// FUNCIONALIDAD SEMI-PUBLICA
	// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	// -------------------------------------------------
	// Función para Logarse
	public function acceso()
	{
		// Cambiar zona horaria
		date_default_timezone_set("America/Mexico_City");
		// Se carga librerias
		$this->load->library('session');
		$this->load->helper('url');
		// Cargar modelo de funciones
		$this->load->model('fncs_mdl');
		// Validaciones, si es por médtodo POST
		if ($this->input->method() === 'post') {
			// Recuperar parametros
			$usr = $this->input->post('u', TRUE);
			$pass = $this->input->post('p', TRUE);
			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Limpiar de variable sobre los parametros
			$usr = $dbtecint->escape_str($usr);
			$pass = $dbtecint->escape_str($pass);
			// Pequeña validación
			if (empty($usr)) {
				$usr = '';
			}
			if (empty($pass)) {
				$pass = '';
			}
			// Ajuste obtener el valor original de los parametros
			$usr = $dbtecint->escape_str(hex2bin ($usr));
			$pass = $dbtecint->escape_str(hex2bin ($pass));
			// Cerrar conexión a db
			$dbtecint->close();

			// Validar tiempo e intentos de inicio de sesión
			if (!$this->validarIntentos()) {
				// Se salva accion
				$this->salvarAccion(3, '[Intento.u:\"'.$usr.'\"]');
				// Regresar error
				echo ("N|Se ha superado el límite de intentos para iniciar de sesión, por favor trata de nuevo en 10 minutos");
				return;
			}

			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Seleccionar datos de la BD
			$dbtecint->select('t_usr.reg, t_usr.nombre, t_usr.correo, t_usr.contrasenia, t_tus.tipo, t_usr.estado');
			$dbtecint->from('usr_sys AS t_usr');
			$dbtecint->join('tipo_usr AS t_tus', 't_usr.reg_tipo = t_tus.reg');
			$dbtecint->where('t_usr.correo =', $usr);
			// Obtener resultado de selección
			$rQry = $dbtecint->get();
			// Cerrar conexión a db
			$dbtecint->close();
			// Bandera
			$isOk = TRUE;
			// Validar número de filas como respuesta
			if ($rQry->num_rows() === 1) {
				// Se toma la única fila que debiese existir
				$rw = $rQry->first_row();
				// Pass
				$pw = $rw->contrasenia;
				// Se valida la contraseña
				if ($pw && !empty($pw) && $this->fncs_mdl->verify_hash($pass, $pw)) {
					// Validar estado del registro
					if ($rw->estado) {
						// Se crea la nueva sessión
						$this->session->set_userdata(array (
							's_nombre' => $rw->nombre,
							's_email' => $rw->correo,
							's_usr' => $rw->reg,
							's_tipo' => $rw->tipo,
						));
						$this->session->set_flashdata('data', $rw->correo);
						$rQry = null;
						$isOk = TRUE;	
					} else {
						$rQry = true;
						$isOk = FALSE;
					}
				} else {
					$rQry = null;
					$isOk = FALSE;
				}
			} else {
				$rQry = null;
				$isOk = FALSE;
			}
			// Si la sesión fue iniciada
			if ($isOk) {
				// Se salva accion
				$this->salvarAccion(3, '');
				// Se emite una respuesta
				echo ("S|/Proyecto_PHP/index.php");
			} else {
				// Validar query
				if ($rQry) {
					// Se salva accion
					$this->salvarAccion(3, '[Intento.u:\"'.$usr.'\"]');
				} else {
					// Se salva accion
					$this->salvarAccion(3, '[Intento.u:\"'.$usr.'\",p:\"'.$pass.'\"]');
				}
				// Se emite una respuesta
				echo ("N");
			}
		} else {
			// VALIDAR SESSIÓN
			// Recuperar sesión
			$sse = $this->session->userdata('s_usr');
			// Validar sesión
			if (!$sse || empty($sse)) {
				// Cargar página principal
				$this->load->view('/_parts/_1_top', array(
					'nombre_pag' => 'Acceso'
				));
				$this->load->view('/_parts/nav', array(
					'titulo' => 'Apoyo en transporte (Xalapa, Ver, México)',
					'pag_name' => 'login'
				));
				$this->load->view('login', array(
					'titulo' => 'Iniciar sesión'
				));
				$this->load->view('/_parts/_2_bot', array(
					'pag_name' => 'login'
				));
			} else {
				// Redireccionar a login
      			header ("Location: " . base_url('/index.php') );
			}
		}
	}
	// -------------------------------------------------
	// Función para cerrar la sesión
	public function cerrar()
	{
		// Cambiar zona horaria
		date_default_timezone_set("America/Mexico_City");
		// Se carga librerias
		$this->load->library('session');
		$this->load->helper('url');
		// Cargar modelo de funciones
		$this->load->model('fncs_mdl');
		// Validaciones
		if ($this->input->method() === 'post') {
			// Se salva accion
			$this->salvarAccion(4, '');
			// Se destruye o limpia todo lo relacionado a la sessión
		    $this->session->unset_userdata('s_nombre');
		    $this->session->unset_userdata('s_email');
		    $this->session->unset_userdata('s_usr');
		    $this->session->unset_userdata('s_tipo');
		    $this->session->sess_destroy();
		    $this->session->set_userdata(array (
				's_nombre' => '',
				's_email' => '',
				's_usr' => '',
				's_tipo' => '',
			));
		    $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		    $this->output->set_header("Pragma: no-cache");
		    // Se emite una respuesta
		    echo ("S|/Proyecto_PHP/index.php/apoyo_en_transporte/acceso");
		} else {
			// Se salva accion
			$this->salvarAccion(4, '[Intento]');
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
		}
	}
	// -------------------------------------------------
	// Función para registro de apoyos
	public function apoyos ()
	{
		// Cambiar zona horaria
		date_default_timezone_set("America/Mexico_City");
		// Se cargar librerias
		$this->load->library('session');
		$this->load->helper('url');
		// Cargar modelo de funciones
		$this->load->model('fncs_mdl');
		// Recuperar sesión
		$sse = $this->session->userdata('s_usr');
		// Validar sesión
		if (!$sse || empty($sse)) {
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}
		// Recupera tipo de usuario
		$stp = $this->session->userdata('s_tipo');
		// Valida si el usuario es de un tipo aceptable
		if (!$stp || empty($stp) ||
			!(($stp == 'super') || ($stp == 'patron'))) {
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}

		// Validar usuario
		if (!$this->validarUsrTipo($sse, $stp)) {
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}

		// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
		// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
		// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
		// Si no existe sesión se espera un acción POST
		if ($this->input->method() === 'post') {
			// >>>>>>>>>>>>>>>>>>>>>
			// Recuperar parametros, filtro XSS
			$reg = $this->input->post('1', TRUE);
			$tipo = $this->input->post('2', TRUE);
			$patron = $this->input->post('3', TRUE);
			$fechaini = $this->input->post('4', TRUE);
			$fechafin = $this->input->post('5', TRUE);
			$hrini = $this->input->post('6', TRUE);
			$hrfin = $this->input->post('7', TRUE);
			$haypy = $this->input->post('8', TRUE);
			// >>>>>>>>>>>>>>>>>>>>>
			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Limpiar de variable
			$reg = $dbtecint->escape_str($reg);
			$tipo = $dbtecint->escape_str($tipo);
			$patron = $dbtecint->escape_str($patron);
			$fechaini = $dbtecint->escape_str($fechaini);
			$fechafin = $dbtecint->escape_str($fechafin);
			$hrini = $dbtecint->escape_str($hrini);
			$hrfin = $dbtecint->escape_str($hrfin);
			$haypy = $dbtecint->escape_str($haypy);
			// Cerrar conexión a BD
			$dbtecint->close();
			// >>>>>>>>>>>>>>>>>>>>>
			// Validar o ajustar parametros
			if (empty($reg)) {
				$reg = '';
			} else {
				$reg = trim($reg);
			}
			if (empty($tipo)) {
				$tipo = '';
			} else {
				$tipo = trim($tipo);
			}
			if (empty($patron)) {
				$patron = '';
			} else {
				$patron = trim($patron);
			}
			if (empty($fechaini)) {
				$fechaini = '';
			} else {
				$fechaini = trim($fechaini);
			}
			if (empty($fechafin)) {
				$fechafin = '';
			} else {
				$fechafin = trim($fechafin);
			}
			if (empty($hrini)) {
				$hrini = '';
			} else {
				$hrini = trim($hrini);
			}
			if (empty($hrfin)) {
				$hrfin = '';
			} else {
				$hrfin = trim($hrfin);
			}
			if (empty($haypy)) {
				$haypy = '';
			} else {
				$haypy = trim($haypy);
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Ajustar valor de parametros luego de formatearlos
			$reg = $dbtecint->escape_str(hex2bin ($reg));
			$tipo = $dbtecint->escape_str(hex2bin ($tipo));
			$patron = $dbtecint->escape_str(hex2bin ($patron));
			$fechaini = $dbtecint->escape_str(hex2bin ($fechaini));
			$fechafin = $dbtecint->escape_str(hex2bin ($fechafin));
			$hrini = $dbtecint->escape_str(hex2bin ($hrini));
			$hrfin = $dbtecint->escape_str(hex2bin ($hrfin));
			$haypy = $dbtecint->escape_str(hex2bin ($haypy));
			// Cerrar conexión a BD
			$dbtecint->close();
			// >>>>>>>>>>>>>>>>>>>>>
			// Validar o ajustar parametros
			if (empty($reg)) {
				$reg = '';
			} else {
				$reg = trim($reg);
			}
			if (empty($tipo)) {
				$tipo = '';
			} else {
				$tipo = trim($tipo);
			}
			if (empty($patron)) {
				$patron = '';
			} else {
				$patron = trim($patron);
			}
			if (empty($fechaini)) {
				$fechaini = '';
			} else {
				$fechaini = trim($fechaini);
			}
			if (empty($fechafin)) {
				$fechafin = '';
			} else {
				$fechafin = trim($fechafin);
			}
			if (empty($hrini)) {
				$hrini = '';
			} else {
				$hrini = trim($hrini);
			}
			if (empty($hrfin)) {
				$hrfin = '';
			} else {
				$hrfin = trim($hrfin);
			}
			if (empty($haypy)) {
				$haypy = '';
			} else {
				$haypy = trim($haypy);
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// >>>>>>>>>>>>>>>>>>>>>
			// >>>>>>>>>>>>>>>>>>>>>
			// Ajuste de valores de acuerdo al tipo
			if ($tipo != '2') {
				// Se omite fecha de inicio, solo se admite si el tipo es "2"
				$fechafin = '';	
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Mensaje de error
			$msjErr = '';
			// Si no existe tipo
			if (empty($tipo)) {
				$msjErr .= "No indicaste el tipo de apoyo.";
			}
			// Si no existe patron
			if (empty($patron)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "No indicaste quien patrocina el apoyo.";
			}
			// Si no existe fecha de incio
			if (empty($fechaini)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				if ($tipo == '2') {
					$msjErr .= "No indicaste la fecha de inicio del apoyo.";
				} else {
					$msjErr .= "No indicaste la fecha del apoyo.";
				}
			}
			// Si no existe fecha final y es de tipo 2
			if (empty($fechafin) && ($tipo == '2')) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "No indicaste la fecha en que finaliza el apoyo.";
			}
			// Si no existe indicación sobre el apoyo
			if (empty($haypy)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "No indicaste si hay o no hay apoyo.";
			}
			// Si no existe la hora de inicio y hay apoyo
			if (empty($hrini) && ($haypy === 'S')) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "No indicaste la hora de inicio del apoyo.";
			}
			// Si no existe la hora final, y hay apoyo o existe una hora de inicio
			if (empty($hrfin) && (!empty($hrini) || ($haypy === 'S'))) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "No indicaste la hora en que finaliza el apoyo.";
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Limpiar mensaje de error
			$msjErr = trim($msjErr);
			// Validar mensaje de error
			if (!empty($msjErr)) {
				// Emitir mensaje de error
				echo($msjErr);
				return;
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Validar tipo
			if ($reg && !empty($reg)) {
				try {
					intval ($reg);
					if (!$this->fncs_mdl->validateNum($reg)) {
						if ($msjErr && !empty($msjErr)) {
							$msjErr .= "\n";
						}
						$msjErr .= "No se puede hacer referencia a este registro.";
					}
				} catch (Exception $e) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					$msjErr .= "No se puede hacer referencia a este registro.";
				}
			}
			try {
				intval ($tipo);
				if (!$this->fncs_mdl->validateNum($tipo)) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					$msjErr .= "El tipo de apoyo tiene un formato inadecuado.";
				}
			} catch (Exception $e) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "El tipo de apoyo tiene un formato inadecuado.";
			}
			try {
				intval ($patron);
				if (!$this->fncs_mdl->validateNum($patron)) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					$msjErr .= "El patrocinador tiene un formato inadecuado.";
				}
			} catch (Exception $e) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "El patrocinador tiene un formato inadecuado.";
			}
			// Bandera de error
			$bandError = false;
			// Validar la existencia de la fecha incial
			if ($fechaini && !empty($fechaini)) {
				try {
					// Validar si la fecha viene en el formato adecuado
					if (DateTime::createFromFormat('Y-m-d', $fechaini)) {
						// Se reformatea la fecha
						$fechaini = date('Y-m-d', strtotime($fechaini));
					} else {
						$bandError = true;
					}
				} catch (Exception $e) {
					$bandError = true;
				}
			} else {
				$bandError = true;
			}
			if ($bandError) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				if ($tipo == '2') {
					$msjErr .= "La fecha de inicio del apoyo tiene un formato inadecuado.";
				} else {
					$msjErr .= "La fecha del apoyo tiene un formato inadecuado.";
				}
			}
			// Reiniciar bandera
			$bandError = false;
			// Validar la existencia de la fecha final
			if ($fechafin && !empty($fechafin)) {
				try {
					// Validar si la fecha viene en el formato adecuado
					if (DateTime::createFromFormat('Y-m-d', $fechafin)) {
						// Se reformatea la fecha
						$fechafin = date('Y-m-d', strtotime($fechafin));
					} else {
						$bandError = true;
					}
				} catch (Exception $e) {
					$bandError = true;
				}
			}
			if ($bandError) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "La fecha en que finaliza el apoyo tiene un formato inadecuado.";
			}
			// Validar si existe la hora de inicio
			if ($hrini && !empty($hrini)) {
				// Validar el formato de la hora
				if (!$this->fncs_mdl->validateTime($hrini)) {
					$hrini .= ":00";
				}
				try {
					// Reformatear y validar que la hora tenga el formato adecuado
					$hrini = date('H:i:s', strtotime("2000-01-01 ".$hrini));
				} catch (Exception $e) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					$msjErr .= "La hora de inicio del apoyo tiene un formato inadecuado.";
				}
			}
			// Validar si existe la hora final
			if ($hrfin && !empty($hrfin)) {
				// Validar el formato de la hora
				if (!$this->fncs_mdl->validateTime($hrfin)) {
					$hrfin .= ":00";
				}
				try {
					// Reformatear y validar que la hora tenga el formato adecuado
					$hrfin = date('H:i:s', strtotime("2000-01-01 ".$hrfin));
				} catch (Exception $e) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					$msjErr .= "La hora en que finaliza el apoyo tiene un formato inadecuado.";
				}
			}

			// >>>>>>>>>>>>>>>>>>>>>
			// Limpiar mensaje de error
			$msjErr = trim($msjErr);
			// Validar mensaje de error
			if (!empty($msjErr)) {
				// Emitir mensaje de error
				echo($msjErr);
				return;
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Registro actual
			$rowEdit = null;
			// Validar
			if ($reg && !empty($reg)) {
				// Conectar base de datos
				$dbtecint = $this->load->database('tecint', true);
				// Consultar datos con base en la fecha actual
				$dbtecint->distinct();
				$dbtecint->select('t_at.reg, t_at.reg_tipo, t_ta.tipo, t_at.hay_apy, t_at.dia_ini, t_at.dia_fin, t_at.hr_ini, t_at.hr_fin, t_at.reg_patron, t_p.patron, t_p.direccion, t_p.tels');
				$dbtecint->from('apy_transport AS t_at');
				$dbtecint->join('tipo_apy AS t_ta', 't_at.reg_tipo = t_ta.reg');
				// Validar tipo de usuario
				if ($stp != 'super') {
					// Agregar JOIN y WHERE
					$dbtecint->join(
						'rel_patron_usr AS t_r',
						't_r.reg_patron = t_at.reg_patron');
					$dbtecint->join('patrocinador AS t_p', 't_r.reg_patron = t_p.reg');	
				} else {
					$dbtecint->join('patrocinador AS t_p', 't_at.reg_patron = t_p.reg');	
				}
				// Validar tipo de usuario
				if ($stp != 'super') {
					$dbtecint->where('t_r.reg_usr =', $sse);
				}
				// Registro
				$dbtecint->where('t_at.reg =', $reg);
				// Obtener resultado de selección
				$miQuery = $dbtecint->get();
				// Cerrar conexión a db
				$dbtecint->close();
				// Reiniciar bandera
				$bandError = false;
				// Validar resultado
				if ($miQuery->num_rows() > 0) {
					$rowEdit = $miQuery->row();
					if (!isset($rowEdit)) {
						$bandError = true;
						$rowEdit = null;
					}
				} else {
					$bandError = true;
					$rowEdit = null;
				}
				// Si no se encuentra el registro
				if ($bandError) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					$msjErr .= "No puedes editar este registro.";
				}
			}

			// >>>>>>>>>>>>>>>>>>>>>
			// Recuperar fecha actual
			$dateA = date('Y-m-d');
			// Validar si existe el registro de apoyo (Si es edición)
			if (!$rowEdit || empty($rowEdit)) {
				// INSERTAR
				// Si es inserción, se valida que la fecha de inicio no sea menor a la fecha actual
				if (strtotime($fechaini) < strtotime($dateA)) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					if ($tipo == '2') {
						$msjErr .= "No puedes registrar un apoyo en transporte con una fecha inicial anterior a la fecha actual.";
					} else {
						$msjErr .= "No puedes registrar un apoyo en transporte con una fecha anterior a la fecha actual.";
					}
				}
			} else {
				// EDITAR
				// Si es edición, se valida que la fecha de inicio no cambie en caso de
				if ((strtotime($rowEdit->dia_ini) <= strtotime($dateA)) &&
					(strtotime($rowEdit->dia_ini) != strtotime($fechaini))) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					if ($tipo == '2') {
						$msjErr .= "No puedes cambiar la fecha de inicio de un apoyo en transporte que originalmente tuviera una fecha de inicio igual o menor a la fecha actual.";
					} else {
						$msjErr .= "No puedes cambiar la fecha de un apoyo en transporte que originalmente tuviera una fecha igual o menor a la fecha actual.";
					}
				}
				// Si es edicion, se valida la existencia de la fecha final
				if ($fechafin && !empty($fechafin)) {
					// Se valida que la fecha final no sea menor a la fecha actual
					if (strtotime($fechafin) < strtotime($dateA)) {
						if ($msjErr && !empty($msjErr)) {
							$msjErr .= "\n";
						}
						$msjErr .= "No puedes editar y dar a un apoyo en transporte una fecha final anterior a la fecha actual.";
					}
				} else {
					// Se valida que la fecha de inicio no sea menor a la fecha actual
					if (strtotime($fechaini) < strtotime($dateA)) {
						if ($msjErr && !empty($msjErr)) {
							$msjErr .= "\n";
						}
						if ($tipo == '2') {
							$msjErr .= "No puedes editar y dar un apoyo en transporte una fecha inicial anterior a la fecha actual.";
						} else {
							$msjErr .= "No puedes editar y dar un apoyo en transporte una fecha anterior a la fecha actual.";
						}
					}	
				}
			}
			// Se valida si la fecha final existe
			if ($fechafin && !empty($fechafin)) {
				// Se valida que la fecha de inicio no sea mayor que la fecha final
				if (strtotime($fechaini) > strtotime($fechafin)) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					$msjErr .= "La fecha de inicial no puede ser mayor que la fecha final.";
				}
			}
			// Se valida que exista la hora inicial y la hora final
			if (($hrini && !empty($hrini)) && ($hrfin && !empty($hrfin))) {
				// Se valida que la hora inicial no sea mayor que la hora final
				if ((new DateTime('2000-10-10'.$hrini)) > (new DateTime('2000-10-10'.$hrfin))) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					$msjErr .= "La hora de inicial no puede ser mayor que la hora final.";
				}
			}

			// >>>>>>>>>>>>>>>>>>>>>
			// Limpiar mensaje de error
			$msjErr = trim($msjErr);
			// Validar mensaje de error
			if (!empty($msjErr)) {
				// Emitir mensaje de error
				echo($msjErr);
				return;
			}

			// >>>>>>>>>>>>>>>>>>>>>
			// Ajuste del contenido de algunos campos
			if (!$fechafin || empty($fechafin)) {
				$fechafin = null;
			}
			if (!$hrini || empty($hrini)) {
				$hrini = null;
			}
			if (!$hrfin || empty($hrfin)) {
				$hrfin = null;
			}
			if ($haypy == 'S') {
				$haypy = true;
			} else {
				$haypy = false;
			}

			// >>>>>>>>>>>>>>>>>>>>>
			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Consultar datos
			$dbtecint->select('t_at.dia_ini, t_at.dia_fin, t_at.reg_patron');
			$dbtecint->from('apy_transport AS t_at');
			
			// Si es EDICIÓN
			if ($reg && !empty($reg)) {
				// No incluir en la consulta al registro en edición
				$dbtecint->where('t_at.reg <>',$reg);
			}
			// Se debe discriminar al patron
			$dbtecint->where('t_at.reg_patron =', $patron);
			
			// Si es EDICIÓN
			if ($reg && !empty($reg)) {
				// EDITAR
				// Si existe la fecha final
				if ($fechafin && !empty($fechafin)) {
					// Donde el dia de inicio sea mayor o igual a la fecha inicial
					$dbtecint
						->group_start()
						->or_where([
							// Donde el dia de inicio sea mayor o igual a la fecha inicial
							't_at.dia_ini >=' => $fechaini,
							// O el dia final sea mayor o igual a la fecha inicial
							't_at.dia_fin >=' => $fechaini
						])
						->group_end();	
					// Donde el dia de inicio sea menor o igual a la fecha final
					$dbtecint->where('t_at.dia_ini <=', $fechafin);
				} else {
					$dbtecint
						->group_start()
						->or_where([
							// Donde el dia de inicio sea igual a la fecha inicial
							't_at.dia_ini =' => $fechaini,
							// O donde el dia final sea igual a la fecha inicial
							't_at.dia_fin =' => $fechaini
						])
						->group_end();
				}
			} else {
				// INSERTAR
				$dbtecint
					->group_start()
					->or_where([
						// Donde el dia de inicio sea mayor o igual a la fecha inicial
						't_at.dia_ini >=' => $fechaini,
						// O el dia final sea mayor o igual a la fecha inicial
						't_at.dia_fin >=' => $fechaini
					])
					->group_end();	
			}
			// Obtener resultado de selección
			$miQuery = $dbtecint->get();
			// Cerrar conexión a db
			$dbtecint->close();
			// Contar número de registros con los cuales chocan las fechas
			if ( $miQuery->num_rows() > 0 ) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
			    $msjErr .= "La fecha de inicio asignada choca con la fecha de inicio o fin de otro registro.";
			}

			// >>>>>>>>>>>>>>>>>>>>>
			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Consultar datos
			$dbtecint->select('t.reg');
			$dbtecint->from('tipo_apy AS t');
			// Se debe discriminar al patron
			$dbtecint->where('t.reg =', $tipo);
			// Obtener resultado de selección
			$miQuery = $dbtecint->get();
			// Cerrar conexión a db
			$dbtecint->close();
			// Contar número de registros
			if ( $miQuery->num_rows() <= 0 ) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
			    $msjErr .= "Ha seleccionado un tipo de registro no válido.";
			}

			// >>>>>>>>>>>>>>>>>>>>>
			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Consultar datos
			$dbtecint->select('t.reg');
			$dbtecint->from('patrocinador AS t');
			// Se debe discriminar al patron
			$dbtecint->where('t.reg =', $patron);
			// Obtener resultado de selección
			$miQuery = $dbtecint->get();
			// Cerrar conexión a db
			$dbtecint->close();
			// Contar número de registros
			if ( $miQuery->num_rows() <= 0 ) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
			    $msjErr .= "Ha seleccionado un patrocinador no válido.";
			}

			// >>>>>>>>>>>>>>>>>>>>>
			// Limpiar mensaje de error
			$msjErr = trim($msjErr);
			// Validar mensaje de error
			if (!empty($msjErr)) {
				// Emitir mensaje de error
				echo($msjErr);
				return;
			}

			// Si es edición
			if ($reg && !empty($reg)) {
				// Se registra cambio
				$this->registrarCambio (1, $reg,
					'[reg_tipo=' . $tipo . ',' .
					 'reg_patron=' . $patron . ',' .
					 'dia_ini=' . $fechaini . ',' .
					 'dia_fin=' . $fechafin . ',' .
					 'hr_ini=' . $hrini . ',' .
					 'hr_fin=' . $hrfin . ',' .
					 'hay_apy=' . $haypy . ']'.
					'[reg_tipo=' . $rowEdit->reg_tipo . ',' .
					'reg_patron=' . $rowEdit->reg_patron  . ',' .
					 'dia_ini=' . $rowEdit->dia_ini  . ',' .
					 'dia_fin=' . $rowEdit->dia_fin  . ',' .
					 'hr_ini=' . $rowEdit->hr_ini  . ',' .
					 'hr_fin=' . $rowEdit->hr_fin  . ',' .
					  'hay_apy=' . $rowEdit->hay_apy  . ']', '[EDITAR]');
			} else {
				// Se registra inserción
				$this->registrarCambio (1, null,
					'[reg_tipo=' . $tipo . ',' .
					 'reg_patron=' . $patron . ',' .
					 'dia_ini=' . $fechaini . ',' .
					 'dia_fin=' . $fechafin . ',' .
					 'hr_ini=' . $hrini . ',' .
					 'hr_fin=' . $hrfin . ',' .
					 'hay_apy=' . $haypy . ']', '[INSERTAR]');
			}
			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Si es edicipon
			if ($reg && !empty($reg)) {
				// Se actualiza registro
				$dbtecint->where('reg', $reg);
					$dbtecint->set('reg_tipo', $tipo);
					$dbtecint->set('reg_patron', $patron);
					$dbtecint->set('dia_ini', $fechaini);
					$dbtecint->set('dia_fin', $fechafin);
					$dbtecint->set('hr_ini', $hrini);
					$dbtecint->set('hr_fin', $hrfin);
					$dbtecint->set('hay_apy', $haypy);
				$dbtecint->update('apy_transport');
			} else {
				// Se inserta registro
				$dbtecint->insert('apy_transport', array (
					'reg_tipo' => $tipo,
					'reg_patron' => $patron,
					'dia_ini' => $fechaini,
					'dia_fin' => $fechafin,
					'hr_ini' => $hrini,
					'hr_fin' => $hrfin,
					'hay_apy' => $haypy,
				));
			}
			// Cerrar conexión a db
			$dbtecint->close();
			// Emitir respuesta
			echo('S|');
			// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
			// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
			// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		} else {
			// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
			// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
			// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
			// C1
			// Conectar base de datos - C1
			$dbtecint1 = $this->load->database('tecint', true);
			$dbtecint1->distinct();
			$dbtecint1->select('t_p.reg, t_p.patron, t_p.direccion, t_p.tels');
			$dbtecint1->from('patrocinador AS t_p');
			// Validar tipo de usuario
			if ($stp != 'super') {
				// Agregar JOIN y WHERE
				$dbtecint1->join(
					'rel_patron_usr AS t_r',
					't_p.reg = t_r.reg_patron');
				$dbtecint1->where('t_r.reg_usr =', $sse);
			}
			// Obtener resultado de selección
			$miQuery1 = $dbtecint1->get();
			// Cerrar conexión a db - C1
			$dbtecint1->close();

			// Componer datos
			foreach($miQuery1->result() as $row) {
				if ($row->tels && !empty($row->tels)) {
					$mTels =  explode ('|', $row->tels);
					$row->tels = '';
					foreach($mTels as $tel) {
						if ($row->tels && !empty($row->tels)) {
							if ($tel && !empty($tel)) {
								$row->tels .= (', '.$tel);
							}
						} else {
							$row->tels = $tel;
						}
					}
				} else {
					$row->tels = 'S/N';
				}
			}

			// C2
			// Conectar base de datos - C2
			$dbtecint2 = $this->load->database('tecint', true);
			// Seleccionar datos de la BD
			$dbtecint2->select('t.reg, t.tipo');
			$dbtecint2->from('tipo_apy AS t');
			// Obtener resultado de selección
			$miQuery2 = $dbtecint2->get();
			// Cerrar conexión a db - C2
			$dbtecint2->close();

			// Componer datos
			foreach($miQuery2->result() as $row) {
				switch ($row->tipo) {
					case "Rango":
						$row->tipo = $this->porRango;
						break;
					case "Por dia":
						$row->tipo = $this->porDia;
						break;
					default:
						$row->tipo = "";
						break;
				}
			}

			// Cargar página principal
			$this->load->view('/_parts/_1_top', array(
				'nombre_pag' => 'Apoyos'
			));
			$this->load->view('/_parts/nav', array(
				'titulo' => 'Apoyo en transporte (Xalapa, Ver, México)',
				'pag_name' => 'apy'
			));
			$this->load->view('/_usrs/apoyos', array(
				'titulo' => 'Edición sobre apoyos en transporte',
				'patrones' => $miQuery1->result(),
				'tipos_apy' => $miQuery2->result(),
			));
			$this->load->view('/_parts/_2_bot', array(
				'pag_name' => 'apy'
			));
		}
		// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	}
	// -------------------------------------------------
	// Función para obtener datos JSON
	public function lista ()
	{
		// Cambiar zona horaria
		date_default_timezone_set("America/Mexico_City");
		// Se cargar librerias
		$this->load->library('session');
		$this->load->helper('url');
		// Cargar modelo de funciones
		$this->load->model('fncs_mdl');

		// Recuperar sesión
		$sse = $this->session->userdata('s_usr');
		// Validar sesión
		if (!$sse || empty($sse)) {
			// Se salva accion
			$this->salvarAccion(2, '[Intento]');
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}
		// Recupera tipo de usuario
		$stp = $this->session->userdata('s_tipo');
		// Valida si el usuario es de un tipo aceptable
		if (!$stp || empty($stp) ||
			!(($stp == 'super') || ($stp == 'patron'))) {
			// Se salva accion
			$this->salvarAccion(2, '[Intento]');
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}

		// Validar usuario
		if (!$this->validarUsrTipo($sse, $stp)) {
			// Se salva accion
			$this->salvarAccion(2, '[Intento]');
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}

		// Se salva accion
		$this->salvarAccion(2, '');
		// Tomar fecha actual
		$fecha = date('Y-m-d');
		// Conectar base de datos
		$dbtecint = $this->load->database('tecint', true);
		// Consultar datos con base en la fecha actual
		$dbtecint->select('t_at.reg, t_at.reg_tipo, t_ta.tipo, t_at.hay_apy, t_at.dia_ini, t_at.dia_fin, t_at.hr_ini, t_at.hr_fin, t_at.reg_patron, t_p.patron, t_p.direccion, t_p.tels');
		$dbtecint->from('apy_transport AS t_at');
		$dbtecint->join('tipo_apy AS t_ta', 't_at.reg_tipo = t_ta.reg');

		// Validar tipo de usuario
		if ($stp != 'super') {
			// Agregar JOIN y WHERE
			$dbtecint->join(
				'rel_patron_usr AS t_r',
				't_r.reg_patron = t_at.reg_patron');
			$dbtecint->join('patrocinador AS t_p', 't_r.reg_patron = t_p.reg');	
		} else {
			$dbtecint->join('patrocinador AS t_p', 't_at.reg_patron = t_p.reg');	
		}

		// Validar tipo de usuario
		if ($stp != 'super') {
			$dbtecint->where('t_r.reg_usr =', $sse);
			$dbtecint->where('t_at.dia_ini >=', $fecha);
			$dbtecint->or_where('t_at.dia_fin >=', $fecha);
			$dbtecint->where('t_r.reg_usr =', $sse);
		} else {
			$dbtecint->where('t_at.dia_ini >=', $fecha);
			$dbtecint->or_where('t_at.dia_fin >=', $fecha);	
		}
		
		$dbtecint->order_by("t_at.dia_ini", "asc");
		// Obtener resultado de selección
		$miQuery = $dbtecint->get();
		// Cerrar conexión a db
		$dbtecint->close();
		// Componer datos
		foreach($miQuery->result()  as $row) {
			// Cambiar valor de "TIPO"
			switch ($row->tipo) {
				case "Rango":
					$row->tipo = $this->porRango;
					break;
				case "Por dia":
					$row->tipo = $this->porDia;
					break;
				default:
					$row->tipo = "";
					break;
			}
		}
		// Devolver datos en formato JSON
		return ($this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(
				json_encode($miQuery->result())
			));
	}
	// -------------------------------------------------
	// Función para registro de usuarios
	public function usuarios()
	{
		// Cambiar zona horaria
		date_default_timezone_set("America/Mexico_City");
		// Se cargar librerias
		$this->load->library('session');
		$this->load->helper('url');
		// Cargar modelo de funciones
		$this->load->model('fncs_mdl');
		// Recuperar sesión
		$sse = $this->session->userdata('s_usr');
		// Validar sesión
		if (!$sse || empty($sse)) {
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}
		// Recupera tipo de usuario
		$stp = $this->session->userdata('s_tipo');
		// Valida si el usuario es de un tipo aceptable
		if (!$stp || empty($stp) ||
			!(($stp == 'super') || ($stp == 'director'))) {
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}

		// Validar usuario
		if (!$this->validarUsrTipo($sse, $stp)) {
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}

		// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
		// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
		// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
		// Si no existe sesión se espera un acción POST
		if ($this->input->method() === 'post') {
			// >>>>>>>>>>>>>>>>>>>>>
			// Recuperar parametros, filtro XSS
			$reg = $this->input->post('1', TRUE);
			$tipo = $this->input->post('2', TRUE);
			$patrones = $this->input->post('3', TRUE);
			$correo = $this->input->post('4', TRUE);
			$nombre = $this->input->post('5', TRUE);
			$ap = $this->input->post('6', TRUE);
			$am = $this->input->post('7', TRUE);
			$pass = $this->input->post('8', TRUE);
			$cpass = $this->input->post('9', TRUE);
			$estado = $this->input->post('10', TRUE);
			// >>>>>>>>>>>>>>>>>>>>>
			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Limpiar de variable
			$reg = $dbtecint->escape_str($reg);
			$tipo = $dbtecint->escape_str($tipo);
			$patrones = $dbtecint->escape_str($patrones);
			$correo = $dbtecint->escape_str($correo);
			$nombre = $dbtecint->escape_str($nombre);
			$ap = $dbtecint->escape_str($ap);
			$am = $dbtecint->escape_str($am);
			$pass = $dbtecint->escape_str($pass);
			$cpass = $dbtecint->escape_str($cpass);
			$estado = $dbtecint->escape_str($estado);
			// Cerrar conexión a BD
			$dbtecint->close();
			// >>>>>>>>>>>>>>>>>>>>>
			// Validar o ajustar parametros
			if (empty($reg)) {
				$reg = '';
			} else {
				$reg = trim($reg);
			}
			if (empty($tipo)) {
				$tipo = '';
			} else {
				$tipo = trim($tipo);
			}
			if (empty($patrones)) {
				$patrones = '';
			} else {
				$patrones = trim($patrones);
			}
			if (empty($correo)) {
				$correo = '';
			} else {
				$correo = trim($correo);
			}
			if (empty($nombre)) {
				$nombre = '';
			} else {
				$nombre = trim($nombre);
			}
			if (empty($ap)) {
				$ap = '';
			} else {
				$ap = trim($ap);
			}
			if (empty($am)) {
				$am = '';
			} else {
				$am = trim($am);
			}
			if (empty($pass)) {
				$pass = '';
			} else {
				$pass = trim($pass);
			}
			if (empty($cpass)) {
				$cpass = '';
			} else {
				$cpass = trim($cpass);
			}
			if (empty($estado)) {
				$estado = '';
			} else {
				$estado = trim($estado);
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Ajustar valor de parametros luego de formatearlos
			$reg = $dbtecint->escape_str(hex2bin ($reg));
			$tipo = $dbtecint->escape_str(hex2bin ($tipo));
			$patrones = $dbtecint->escape_str(hex2bin ($patrones));
			$correo = $dbtecint->escape_str(hex2bin ($correo));
			$nombre = $dbtecint->escape_str(hex2bin ($nombre));
			$ap = $dbtecint->escape_str(hex2bin ($ap));
			$am = $dbtecint->escape_str(hex2bin ($am));
			$pass = $dbtecint->escape_str(hex2bin ($pass));
			$cpass = $dbtecint->escape_str(hex2bin ($cpass));
			$estado = $dbtecint->escape_str(hex2bin ($estado));
			// UTF8
			$correo = utf8_encode($correo);
			$nombre = utf8_encode($nombre);
			$ap = utf8_encode($ap);
			$am = utf8_encode($am);
			// Cerrar conexión a BD
			$dbtecint->close();
			// >>>>>>>>>>>>>>>>>>>>>
			// Validar o ajustar parametros
			if (empty($reg)) {
				$reg = '';
			} else {
				$reg = trim($reg);
			}
			if (empty($tipo)) {
				$tipo = '';
			} else {
				$tipo = trim($tipo);
			}
			if (empty($patrones)) {
				$patrones = '';
			} else {
				$patrones = trim($patrones);
			}
			if (empty($correo)) {
				$correo = '';
			} else {
				$correo = trim($correo);
			}
			if (empty($nombre)) {
				$nombre = '';
			} else {
				$nombre = trim($nombre);
			}
			if (empty($ap)) {
				$ap = '';
			} else {
				$ap = trim($ap);
			}
			if (empty($am)) {
				$am = '';
			} else {
				$am = trim($am);
			}
			if (empty($pass)) {
				$pass = '';
			} else {
				$pass = trim($pass);
			}
			if (empty($cpass)) {
				$cpass = '';
			} else {
				$cpass = trim($cpass);
			}
			if (empty($estado)) {
				$estado = '';
			} else {
				$estado = trim($estado);
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// >>>>>>>>>>>>>>>>>>>>>
			// >>>>>>>>>>>>>>>>>>>>>
			// Ajuste de valores de acuerdo al tipo
			if ($tipo != '3') {
				// Se omiten los patrocinadores, solo se admite si el tipo es "3"
				$patrones = '';
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Mensaje de error
			$msjErr = '';
			// Validaciones base
			if (!$tipo || empty($tipo)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "No indicaste el tipo de usuario.";
			}
			if ((!$patrones || empty($patrones)) && ($tipo === '3')) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "No indicaste patrocinadores relacionados a este usuario.";
			}
			if (!$correo || empty($correo)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "No indicaste el correo electrónico del usuario.";
			}
			if (!$nombre || empty($nombre)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "No indicaste el nombre del usuario.";
			}
			if (!$ap || empty($ap)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "No indicaste el apellido paterno del usuario.";
			}
			if ((($pass && !empty($pass)) || ($cpass && !empty($cpass))) && ($pass !== $cpass)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "Las contraseñas no coinciden.";
			}

			if (($pass && !empty($pass)) && (strlen($pass) < 8)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "La contraseña que indicaste no puede ser menor a 8 caracteres.";
			}

			if (($pass && !empty($pass)) && !$this->fncs_mdl->validatePass($pass)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "La contraseña debe incluir al menos una letra mayúscula, una letra minúscula, un número, y tiene que iniciar y terminar con un valor alfanumérico.";
			}
			if ((!$reg || empty($reg)) && (!$pass || empty($pass))) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "Falta indicar la contraseña.";
			}
			if (strlen($correo) > 50) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "El correo electrónico es demasiado grande.";
			}
			if (strlen($nombre) > 50) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "El nombre es demasiado grande.";
			}
			if (strlen($ap) > 25) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "El apellido paterno es demasiado grande.";
			}
			if (($am && !empty($am)) && (strlen($am) > 25)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "El apellido materno es demasiado grande.";
			}
			if (($pass && !empty($pass)) && (strlen($pass) > 100)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "La contraseña es demasiado grande.";
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Ajuste de valores
			if ($pass && !empty($pass)) {
				$pass = $this->fncs_mdl->get_hash($pass);	
			}
			$patrones = explode('|', $patrones);
			$patronesR = '';
			// Ajustar estado de patrones
			if ($estado === 'S') {
				$estado = true;
			} else {
				$estado = false;
			}
			// Validar datos sobre PATRONES
			foreach($patrones as $np) {
				// Ajuste y validación de formato
				$np = trim($np);
				if ($np && !empty($np)) {
					try {
						intval ($np);
						if (!$this->fncs_mdl->validateNum($np)) {
							if ($msjErr && !empty($msjErr)) {
								$msjErr .= "\n";
							}
							$msjErr .= "El patrocinador tiene un formato inadecuado.";
							break;
						}
					} catch (Exception $e) {
						if ($msjErr && !empty($msjErr)) {
							$msjErr .= "\n";
						}
						$msjErr .= "El patrocinador tiene un formato inadecuado.";
						break;
					}
					// Conectar base de datos
					$dbtecint = $this->load->database('tecint', true);
					// Consultar datos
					$dbtecint->select('t.reg');
					$dbtecint->from('patrocinador AS t');
					// Se debe discriminar al patron
					$dbtecint->where('t.reg =', $np);
					// Obtener resultado de selección
					$miQuery = $dbtecint->get();
					// Cerrar conexión a db
					$dbtecint->close();
					// Contar número de registros
					if ( $miQuery->num_rows() <= 0 ) {
						if ($msjErr && !empty($msjErr)) {
							$msjErr .= "\n";
						}
					    $msjErr .= "Ha seleccionado un patrocinador no válido.";
					    break;
					}
					// Respaldo de patrones
					if (!$patronesR || empty($patronesR)) {
						$patronesR .= $np;
					} else {
						$patronesR .= '|'.$np;
					}
				}
			}
			$patrones = explode('|', $patronesR);
			// >>>>>>>>>>>>>>>>>>>>>
			// Validar si puedo registrar este tipo de usuario
			if (($tipo == '1'/*A*/) || ($tipo == '2'/*D*/)) {
				if ($stp != 'super') {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					$msjErr .= "Tipo de usuario no válido.";
				}
			} // En otro caso es 3 o R
			// >>>>>>>>>>>>>>>>>>>>>
			// Limpiar mensaje de error
			$msjErr = trim($msjErr);
			// Validar mensaje de error
			if (!empty($msjErr)) {
				// Emitir mensaje de error
				echo($msjErr);
				return;
			}
			// Validaciones de formato
			if ($reg && !empty($reg)) {
				try {
					intval ($reg);
					if (!$this->fncs_mdl->validateNum($reg)) {
						if ($msjErr && !empty($msjErr)) {
							$msjErr .= "\n";
						}
						$msjErr .= "No se puede hacer referencia a este registro.";
					}
				} catch (Exception $e) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					$msjErr .= "No se puede hacer referencia a este registro.";
				}
			}
			try {
				intval ($tipo);
				if (!$this->fncs_mdl->validateNum($tipo)) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					$msjErr .= "El tipo de apoyo tiene un formato inadecuado.";
				}
			} catch (Exception $e) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "El tipo de apoyo tiene un formato inadecuado.";
			}			
			if (!$this->fncs_mdl->validateEmail($correo)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "El correo electrónico tiene un formato inadecuado.";
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Limpiar mensaje de error
			$msjErr = trim($msjErr);
			// Validar mensaje de error
			if (!empty($msjErr)) {
				// Emitir mensaje de error
				echo($msjErr);
				return;
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Registro actual
			$rowEdit = null;
			$dataPatrones = null;
			// Validar
			if ($reg && !empty($reg)) {
				// Conectar base de datos
				$dbtecint = $this->load->database('tecint', true);
				// Consultar datos
				$dbtecint->distinct();
				$dbtecint->select('t_us.reg, t_us.nombre, t_us.ap_paterno, t_us.ap_materno, t_us.correo, t_us.estado, t_us.reg_tipo, t_tu.tipo');
				$dbtecint->from('usr_sys AS t_us');
				$dbtecint->join('tipo_usr AS t_tu', 't_us.reg_tipo = t_tu.reg');
				// Registro
				$dbtecint->where('t_us.reg =', $reg);
				// Obtener resultado de selección
				$miQuery = $dbtecint->get();
				// Cerrar conexión a db
				$dbtecint->close();
				// Reiniciar bandera
				$bandError = false;
				// Validar resultado
				if ($miQuery->num_rows() > 0) {
					$rowEdit = $miQuery->row();
					if (!isset($rowEdit)) {
						$bandError = true;
						$rowEdit = null;
					} else {
						// Obtener patrones el usuario
						$dataPatrones = $this->getPatron ($rowEdit->reg);
					}
				} else {
					$bandError = true;
					$rowEdit = null;
				}
				// Si no se encuentra el registro
				if ($bandError) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					$msjErr .= "No puedes editar este registro.";
				}
			}
			// Validar
			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Consultar datos
			$dbtecint->distinct();
			$dbtecint->select('t_us.reg, t_us.nombre, t_us.ap_paterno, t_us.ap_materno, t_us.correo, t_us.estado, t_us.reg_tipo, t_tu.tipo');
			$dbtecint->from('usr_sys AS t_us');
			$dbtecint->join('tipo_usr AS t_tu', 't_us.reg_tipo = t_tu.reg');
			// Registro
			if ($reg && !empty($reg)) {
				$dbtecint->where('t_us.reg <>', $reg);
			}
			$dbtecint->where('t_us.correo =', $correo);
			// Obtener resultado de selección
			$miQuery = $dbtecint->get();
			// Cerrar conexión a db
			$dbtecint->close();
			// Validar resultado
			if ($miQuery->num_rows() > 0) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "El correo electrónico indicado esta siendo utilizado por otro usuario.";
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Limpiar mensaje de error
			$msjErr = trim($msjErr);
			// Validar mensaje de error
			if (!empty($msjErr)) {
				// Emitir mensaje de error
				echo($msjErr);
				return;
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Consultar datos
			$dbtecint->select('t.reg');
			$dbtecint->from('tipo_usr AS t');
			// Se debe discriminar al patron
			$dbtecint->where('t.reg =', $tipo);
			// Obtener resultado de selección
			$miQuery = $dbtecint->get();
			// Cerrar conexión a db
			$dbtecint->close();
			// Contar número de registros
			if ( $miQuery->num_rows() <= 0 ) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
			    $msjErr .= "Ha seleccionado un tipo de registro no válido.";
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Limpiar mensaje de error
			$msjErr = trim($msjErr);
			// Validar mensaje de error
			if (!empty($msjErr)) {
				// Emitir mensaje de error
				echo($msjErr);
				return;
			}

			// Si es edición
			if ($reg && !empty($reg)) {
				// Reserva
				$patronesV = '';
				// Si fue edición
				if ($dataPatrones && !empty($dataPatrones)) {
					// BORRAR patrones antiguos
					foreach($dataPatrones as $dt) {
						// Validar
						if ($dt->reg_patron && !empty($dt->reg_patron)) {
							// Respaldo de patrones
							if (!$patronesV || empty($patronesV)) {
								$patronesV .= $dt->reg_patron;
							} else {
								$patronesV .= '|'.$dt->reg_patron;
							}
						}
					}
				}
				// Se registra cambio
				$this->registrarCambio (2, $reg,
					'[reg_tipo=' . $tipo . ',' .
					 'correo=' . $correo . ',' .
					 'nombre=' . $nombre . ',' .
					 'ap_paterno=' . $ap . ',' .
					 'ap_materno=' . $am . ',' .
					 'estado=' . $estado . ',' .
					 'patrocinadores=' . $patronesR . ']'.
					'[reg_tipo=' . $rowEdit->reg_tipo . ',' .
					 'correo=' . $rowEdit->correo  . ',' .
					 'nombre=' . $rowEdit->nombre  . ',' .
					 'ap_paterno=' . $rowEdit->ap_paterno  . ',' .
					 'ap_materno=' . $rowEdit->ap_materno  . ',' .
					 'estado=' . $rowEdit->estado . ',' .
					 'patrocinadores=' . $patronesV . ']', '[EDITAR]');
			} else {
				// Se registra inserción
				$this->registrarCambio (2, null,
					'[reg_tipo=' . $tipo . ',' .
					 'correo=' . $correo . ',' .
					 'nombre=' . $nombre . ',' .
					 'ap_paterno=' . $ap . ',' .
					 'ap_materno=' . $am . ',' .
					 'estado=' . $estado . ',' .
					 'patrocinadores=' . $patronesR . ']', '[INSERTAR]');
			}

			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Si es edición
			if ($reg && !empty($reg)) {
				if (!$pass || empty($pass)) {
					// Se actualiza registro
					$dbtecint->where('reg', $reg);
						$dbtecint->set('reg_tipo', $tipo);
						$dbtecint->set('correo', $correo);
						$dbtecint->set('nombre', $nombre);
						$dbtecint->set('ap_paterno', $ap);
						$dbtecint->set('ap_materno', $am);
						$dbtecint->set('estado', $estado);
					$dbtecint->update('usr_sys');
				} else {
					// Se actualiza registro
					$dbtecint->where('reg', $reg);
						$dbtecint->set('reg_tipo', $tipo);
						$dbtecint->set('correo', $correo);
						$dbtecint->set('nombre', $nombre);
						$dbtecint->set('ap_paterno', $ap);
						$dbtecint->set('ap_materno', $am);
						$dbtecint->set('estado', $estado);
						$dbtecint->set('contrasenia', $pass);
					$dbtecint->update('usr_sys');
				}
			} else {
				// Se inserta registro
				$dbtecint->insert('usr_sys', array (
					'reg_tipo' => $tipo,
					'correo' => $correo,
					'nombre' => $nombre,
					'ap_paterno' => $ap,
					'ap_materno' => $am,
					'estado' => $estado,
					'contrasenia' => $pass,
				));
			}
			// Cerrar conexión a db
			$dbtecint->close();

			// >>>>>>>>>>>>>>>>>>>>>
			// Recuperar registro actualizado o insertado
			if (!$reg || empty($reg)) {
				// Conectar base de datos
				$dbtecint = $this->load->database('tecint', true);
				// Consultar datos
				$dbtecint->distinct();
				$dbtecint->select('t_us.reg, t_us.nombre, t_us.ap_paterno, t_us.ap_materno, t_us.correo, t_us.estado, t_us.reg_tipo, t_tu.tipo');
				$dbtecint->from('usr_sys AS t_us');
				$dbtecint->join('tipo_usr AS t_tu', 't_us.reg_tipo = t_tu.reg');
				// Registro
				$dbtecint->where('t_us.correo =', $correo);
				// Obtener resultado de selección
				$miQuery = $dbtecint->get();
				// Cerrar conexión a db
				$dbtecint->close();
				// Reiniciar bandera
				$bandError = false;
				// Validar resultado
				if ($miQuery->num_rows() > 0) {
					$rowEdit = $miQuery->row();
					if (!isset($rowEdit)) {
						$bandError = true;
						$rowEdit = null;
					}
				} else {
					$bandError = true;
					$rowEdit = null;
				}
			}
			// Validar existencia de registro
			if ($rowEdit) {
				// Si fue edición
				if ($dataPatrones && !empty($dataPatrones)) {
					// BORRAR patrones antiguos
					foreach($dataPatrones as $dt) {
						// Validar
						if ($dt->reg_patron && !empty($dt->reg_patron)) {
							// Conectar base de datos
							$dbtecint = $this->load->database('tecint', true);
							$dbtecint->where('reg_usr =', $rowEdit->reg);
							$dbtecint->where('reg_patron =', $dt->reg_patron);
							$dbtecint->delete('rel_patron_usr');
							// Cerrar conexión a db
							$dbtecint->close();
						}
					}
				}
				// INGRESAR nuevos patrones
				foreach($patrones as $np) {
					if ($np && !empty($np)) {
						// Conectar base de datos
						$dbtecint = $this->load->database('tecint', true);
						// Se inserta registro
						$dbtecint->insert('rel_patron_usr', array (
							'reg_usr' => $rowEdit->reg,
							'reg_patron' => $np,
						));
						// Cerrar conexión a db
						$dbtecint->close();
					}
				}
			}
			// Emitir respuesta
			echo('S|');
			// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
			// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
			// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		} else {
			// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
			// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
			// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv

			// C1
			// Conectar base de datos - C1
			$dbtecint1 = $this->load->database('tecint', true);
			// Seleccionar datos de la BD
			$dbtecint1->select('t_p.reg, t_p.patron, t_p.direccion, , t_p.tels');
			$dbtecint1->from('patrocinador AS t_p');
			// Obtener resultado de selección
			$miQuery1 = $dbtecint1->get();
			// Cerrar conexión a db - C1
			$dbtecint1->close();

			// Componer datos
			foreach($miQuery1->result() as $row) {
				if ($row->tels && !empty($row->tels)) {
					$mTels =  explode ('|', $row->tels);
					$row->tels = '';
					foreach($mTels as $tel) {
						if ($row->tels && !empty($row->tels)) {
							if ($tel && !empty($tel)) {
								$row->tels .= (', '.$tel);
							}
						} else {
							$row->tels = $tel;
						}
					}
				} else {
					$row->tels = 'S/N';
				}
			}

			// C2
			// Conectar base de datos - C2
			$dbtecint2 = $this->load->database('tecint', true);
			$dbtecint2->distinct();
			$dbtecint2->select('t_tus.reg, t_tus.tipo');
			$dbtecint2->from('tipo_usr AS t_tus');
			// Si no es administrador
			if ($stp != 'super') {
				$dbtecint2->where('t_tus.reg =', '3');
			}
			// Obtener resultado de selección
			$miQuery2 = $dbtecint2->get();
			// Cerrar conexión a db - C2
			$dbtecint2->close();

			// Cargar página principal
			$this->load->view('/_parts/_1_top', array(
				'nombre_pag' => 'Usuarios'
			));
			$this->load->view('/_parts/nav', array(
				'titulo' => 'Apoyo en transporte (Xalapa, Ver, México)',
				'pag_name' => 'usr'
			));
			$this->load->view('/_usrs/usuarios', array(
				'titulo' => 'Edición de usuarios',
				'patrones' => $miQuery1->result(),
				'tipos_usr' => $miQuery2->result(),
			));
			$this->load->view('/_parts/_2_bot', array(
				'pag_name' => 'usr'
			));
		}
		// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	}
	// -------------------------------------------------
	// Función para obtener datos JSON de usuarios
	public function usrs ()
	{
		// Cambiar zona horaria
		date_default_timezone_set("America/Mexico_City");
		// Se cargar librerias
		$this->load->library('session');
		$this->load->helper('url');
		// Cargar modelo de funciones
		$this->load->model('fncs_mdl');

		// Recuperar sesión
		$sse = $this->session->userdata('s_usr');
		// Validar sesión
		if (!$sse || empty($sse)) {
			// Se salva accion
			$this->salvarAccion(5, '[Intento]');
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}
		// Recupera tipo de usuario
		$stp = $this->session->userdata('s_tipo');
		// Valida si el usuario es de un tipo aceptable
		if (!$stp || empty($stp) ||
			!(($stp == 'super') || ($stp == 'director'))) {
			// Se salva accion
			$this->salvarAccion(5, '[Intento]');
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}

		// Validar usuario
		if (!$this->validarUsrTipo($sse, $stp)) {
			// Se salva accion
			$this->salvarAccion(5, '[Intento]');
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}

		// Se salva accion
		$this->salvarAccion(5, '');

		// Conectar base de datos
		$dbtecint = $this->load->database('tecint', true);
		// Consultar datos con base en la fecha actual
		$dbtecint->select('t_us.reg, t_us.nombre, t_us.ap_paterno, t_us.ap_materno, t_us.correo, t_us.estado, t_us.reg_tipo, t_tu.tipo');
		$dbtecint->from('usr_sys AS t_us');
		$dbtecint->join('tipo_usr AS t_tu', 't_us.reg_tipo = t_tu.reg');
		// Si no es administrador
		if ($stp != 'super') {
			$dbtecint->where('t_us.reg_tipo =', '3');
		}
		$dbtecint->order_by("t_us.reg, t_us.nombre", "asc");
		// Obtener resultado de selección
		$miQuery = $dbtecint->get();
		// Cerrar conexión a db
		$dbtecint->close();
		// JSON
		$dataAry = array();
		$dataJson = null;
		// Componer datos
		foreach($miQuery->result() as $row) {
			// Obtener patrond el usuario
			$dataPatron = $this->getPatron ($row->reg);
			// Reiniciar variables
			$dataJson = (json_decode ("{}"));
			// Asignar valores
			$dataJson->reg = $row->reg;
			$dataJson->nombre = $row->nombre;
			$dataJson->ap_paterno = $row->ap_paterno;
			$dataJson->ap_materno = $row->ap_materno;
			$dataJson->correo = $row->correo;
			$dataJson->estado = $row->estado;
			$dataJson->reg_tipo = $row->reg_tipo;
			$dataJson->tipo = $row->tipo;
			$dataJson->reg_patron = '';
			$dataJson->patron = '';
			$dataJson->direccion = '';
			$dataJson->tels = '';
			// Validar
			if ($dataPatron && !empty($dataPatron)) {
				// Recorrer patrones
				foreach($dataPatron as $p) {
					if ($p->reg_patron && !empty($p->reg_patron)) {
						if ($dataJson->reg_patron && !empty($dataJson->reg_patron)) {
							$dataJson->reg_patron .= '[|<>|] '.$p->reg_patron;
						} else {
							$dataJson->reg_patron .= $p->reg_patron;
						}
					}
					if ($p->patron && !empty($p->patron)) {
						if ($dataJson->patron && !empty($dataJson->patron)) {
							$dataJson->patron .= '[|<>|] '.$p->patron;
						} else {
							$dataJson->patron .= $p->patron;
						}
					}
					if ($p->direccion && !empty($p->direccion)) {
						if ($dataJson->direccion && !empty($dataJson->direccion)) {
							$dataJson->direccion .= '[|<>|]'.$p->direccion;
						} else {
							$dataJson->direccion .= $p->direccion;
						}
					}
					if ($p->tels && !empty($p->tels)) {
						if ($dataJson->tels && !empty($dataJson->tels)) {
							$dataJson->tels .= '[|<>|] '.$p->tels;
						} else {
							$dataJson->tels .= $p->tels;
						}
					}
				}
			}
			array_push($dataAry, $dataJson);
		}
		// Devolver datos en formato JSON
		return ($this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(
				json_encode($dataAry)
			));
	}
	// -------------------------------------------------
	// Función para obtener datos JSON de usuarios
	private function getPatron ($reg_usr)
	{
		// Conectar base de datos
		$dbtecint = $this->load->database('tecint', true);
		// Consultar datos con base en la fecha actual
		$dbtecint->select('t_p.reg AS reg_patron, t_p.patron, t_p.direccion, t_p.tels');
		$dbtecint->from('rel_patron_usr AS t_rel');
		$dbtecint->join('patrocinador AS t_p', 't_rel.reg_patron = t_p.reg');
		$dbtecint->where("t_rel.reg_usr =", $reg_usr);
		// Obtener resultado de selección
		$miQuery = $dbtecint->get();
		$miQuery = $miQuery->result();
		// Cerrar conexión a db
		$dbtecint->close();
		// Componer datos
		foreach($miQuery as $row) {
			if ($row->tels && !empty($row->tels)) {
				$mTels =  explode ('|', $row->tels);
				$row->tels = '';
				foreach($mTels as $tel) {
					if ($row->tels && !empty($row->tels)) {
						if ($tel && !empty($tel)) {
							$row->tels .= (', '.$tel);
						}
					} else {
						$row->tels = $tel;
					}
				}
			} else {
				$row->tels = 'S/N';
			}
		}
		// Devolver datos en formato JSON
		return $miQuery;
	}
	// -------------------------------------------------
	// Función para registro de patrocinadores
	public function patrocinadores()
	{
		// Cambiar zona horaria
		date_default_timezone_set("America/Mexico_City");
		// Se cargar librerias
		$this->load->library('session');
		$this->load->helper('url');
		// Cargar modelo de funciones
		$this->load->model('fncs_mdl');
		// Recuperar sesión
		$sse = $this->session->userdata('s_usr');
		// Validar sesión
		if (!$sse || empty($sse)) {
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}
		// Recupera tipo de usuario
		$stp = $this->session->userdata('s_tipo');
		// Valida si el usuario es de un tipo aceptable
		if (!$stp || empty($stp) ||
			!(($stp == 'super') || ($stp == 'director'))) {
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}

		// Validar usuario
		if (!$this->validarUsrTipo($sse, $stp)) {
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}

		// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
		// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
		// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
		// Si no existe sesión se espera un acción POST
		if ($this->input->method() === 'post') {
			// >>>>>>>>>>>>>>>>>>>>>
			// Recuperar parametros, filtro XSS
			$reg = $this->input->post('1', TRUE);
			$patron = $this->input->post('2', TRUE);
			$direccion = $this->input->post('3', TRUE);
			$telefonos = $this->input->post('4', TRUE);
			// >>>>>>>>>>>>>>>>>>>>>
			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Limpiar de variable
			$reg = $dbtecint->escape_str($reg);
			$patron = $dbtecint->escape_str($patron);
			$direccion = $dbtecint->escape_str($direccion);
			$telefonos = $dbtecint->escape_str($telefonos);
			// Cerrar conexión a BD
			$dbtecint->close();
			// >>>>>>>>>>>>>>>>>>>>>
			// Validar o ajustar parametros
			if (empty($reg)) {
				$reg = '';
			} else {
				$reg = trim($reg);
			}
			if (empty($patron)) {
				$patron = '';
			} else {
				$patron = trim($patron);
			}
			if (empty($direccion)) {
				$direccion = '';
			} else {
				$direccion = trim($direccion);
			}
			if (empty($telefonos)) {
				$telefonos = '';
			} else {
				$telefonos = trim($telefonos);
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Ajustar valor de parametros luego de formatearlos
			$reg = $dbtecint->escape_str(hex2bin ($reg));
			$patron = $dbtecint->escape_str(hex2bin ($patron));
			$direccion = $dbtecint->escape_str(hex2bin ($direccion));
			$telefonos = $dbtecint->escape_str(hex2bin ($telefonos));
			// Codificar
			$patron = utf8_encode($patron);
			$direccion = utf8_encode($direccion);
			// Cerrar conexión a BD
			$dbtecint->close();
			// >>>>>>>>>>>>>>>>>>>>>
			// Validar o ajustar parametros
			if (empty($reg)) {
				$reg = '';
			} else {
				$reg = trim($reg);
			}
			if (empty($patron)) {
				$patron = '';
			} else {
				$patron = trim($patron);
			}
			if (empty($direccion)) {
				$direccion = '';
			} else {
				$direccion = trim($direccion);
			}
			if (empty($telefonos)) {
				$telefonos = '';
			} else {
				$telefonos = trim($telefonos);
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Mensaje de error
			$msjErr = '';
			// Validaciones base
			if (!$patron || empty($patron)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "No mencionaste el nombre del patrocinador.";
			}
			if (!$direccion || empty($direccion)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "No mencionaste la dirección del patrocinador.";
			}
			if (!$telefonos || empty($telefonos)) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "No mencionaste al menos un telefono para el patrocinador.";
			}
			if (strlen($patron) > 50) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "El nombre del patrocinador es demasiado grande.";
			}
			if (strlen($direccion) > 100) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "La dirección del patrocinador es demasiado grande.";
			}
			if (strlen($telefonos) > 120) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "Estas registrando demasiados números de telefono.";
			}
			// >>>>>>>>>>>>>>>>>>>>>
			$telsArry = explode('|', $telefonos);
			$telefonos = '';
			// Validar datos
			foreach($telsArry as &$tel) {
				$tel = trim($tel);
				if ($telefonos && !empty($telefonos)) {
					$telefonos .= ('|'.$tel);
				} else {
					$telefonos .= $tel;
				}
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Limpiar mensaje de error
			$msjErr = trim($msjErr);
			// Validar mensaje de error
			if (!empty($msjErr)) {
				// Emitir mensaje de error
				echo($msjErr);
				return;
			}
			// Validaciones de formato
			if ($reg && !empty($reg)) {
				try {
					intval ($reg);
					if (!$this->fncs_mdl->validateNum($reg)) {
						if ($msjErr && !empty($msjErr)) {
							$msjErr .= "\n";
						}
						$msjErr .= "No se puede hacer referencia a este registro.";
					}
				} catch (Exception $e) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					$msjErr .= "No se puede hacer referencia a este registro.";
				}
			}
			// Validar datos
			foreach($telsArry as $tel) {
				if (!$this->fncs_mdl->validateTel($tel)) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					$msjErr .= "Algunos números de telefono tienen un formato inadecuado.";
				}
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Limpiar mensaje de error
			$msjErr = trim($msjErr);
			// Validar mensaje de error
			if (!empty($msjErr)) {
				// Emitir mensaje de error
				echo($msjErr);
				return;
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Registro actual
			$rowEdit = null;
			// Validar
			if ($reg && !empty($reg)) {
				// Conectar base de datos
				$dbtecint = $this->load->database('tecint', true);
				// Consultar datos
				$dbtecint->distinct();
				$dbtecint->select('t_p.reg, t_p.patron, t_p.direccion, t_p.tels');
				$dbtecint->from('patrocinador AS t_p');
				// Registro
				$dbtecint->where('t_p.reg =', $reg);
				// Obtener resultado de selección
				$miQuery = $dbtecint->get();
				// Cerrar conexión a db
				$dbtecint->close();
				// Reiniciar bandera
				$bandError = false;
				// Validar resultado
				if ($miQuery->num_rows() > 0) {
					$rowEdit = $miQuery->row();
					if (!isset($rowEdit)) {
						$bandError = true;
						$rowEdit = null;
					}
				} else {
					$bandError = true;
					$rowEdit = null;
				}
				// Si no se encuentra el registro
				if ($bandError) {
					if ($msjErr && !empty($msjErr)) {
						$msjErr .= "\n";
					}
					$msjErr .= "No puedes editar este registro.";
				}
			}
			// Validar
			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Consultar datos
			$dbtecint->distinct();
			$dbtecint->select('t_p.reg, t_p.patron, t_p.direccion, t_p.tels');
			$dbtecint->from('patrocinador AS t_p');
			// Registro
			if ($reg && !empty($reg)) {
				$dbtecint->where('t_p.reg <>', $reg);
			}
			$dbtecint->where('t_p.patron =', $patron);
			// Obtener resultado de selección
			$miQuery = $dbtecint->get();
			// Cerrar conexión a db
			$dbtecint->close();
			// Validar resultado
			if ($miQuery->num_rows() > 0) {
				if ($msjErr && !empty($msjErr)) {
					$msjErr .= "\n";
				}
				$msjErr .= "El nombre de patrocinador indicado ya esta siendo utilizado en otro registro.";
			}
			// >>>>>>>>>>>>>>>>>>>>>
			// Limpiar mensaje de error
			$msjErr = trim($msjErr);
			// Validar mensaje de error
			if (!empty($msjErr)) {
				// Emitir mensaje de error
				echo($msjErr);
				return;
			}
			// Si es edición
			if ($reg && !empty($reg)) {
				// Se registra cambio
				$this->registrarCambio (3, $reg,
					'[patron=' . $patron . ',' .
					 'direccion=' . $direccion . ',' .
					 'tels=' . $telefonos . ']'.
					'[patron=' . $rowEdit->patron . ',' .
					 'direccion=' . $rowEdit->direccion . ',' .
					 'tels=' . $rowEdit->tels . ']', '[EDITAR]');
			} else {
				// Se registra inserción
				$this->registrarCambio (3, null,
					'[patron=' . $patron . ',' .
					 'direccion=' . $direccion . ',' .
					 'tels=' . $telefonos . ']', '[INSERTAR]');
			}

			// Conectar base de datos
			$dbtecint = $this->load->database('tecint', true);
			// Si es edición
			if ($reg && !empty($reg)) {
				// Se actualiza registro
				$dbtecint->where('reg', $reg);
					$dbtecint->set('patron', $patron);
					$dbtecint->set('direccion', $direccion);
					$dbtecint->set('tels', $telefonos);
				$dbtecint->update('patrocinador');
			} else {
				// Se inserta registro
				$dbtecint->insert('patrocinador', array (
					'patron' => $patron,
					'direccion' => $direccion,
					'tels' => $telefonos,
				));
			}
			// Cerrar conexión a db
			$dbtecint->close();
			// Emitir respuesta
			echo('S|');

			// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
			// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
			// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		} else {
			// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
			// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
			// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv

			// Cargar página principal
			$this->load->view('/_parts/_1_top', array(
				'nombre_pag' => 'Patrocinadores'
			));
			$this->load->view('/_parts/nav', array(
				'titulo' => 'Apoyo en transporte (Xalapa, Ver, México)',
				'pag_name' => 'patron'
			));
			$this->load->view('/_usrs/patrones', array(
				'titulo' => 'Edición de Patrocinadores',
			));
			$this->load->view('/_parts/_2_bot', array(
				'pag_name' => 'patron'
			));
		}
		// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	}
	// -------------------------------------------------
	// Función para obtener datos JSON sobre patrocinadores
	public function ptrns ()
	{
		// Cambiar zona horaria
		date_default_timezone_set("America/Mexico_City");
		// Se cargar librerias
		$this->load->library('session');
		$this->load->helper('url');
		// Cargar modelo de funciones
		$this->load->model('fncs_mdl');

		// Recuperar sesión
		$sse = $this->session->userdata('s_usr');
		// Validar sesión
		if (!$sse || empty($sse)) {
			// Se salva accion
			$this->salvarAccion(6, '[Intento]');
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}
		// Recupera tipo de usuario
		$stp = $this->session->userdata('s_tipo');
		// Valida si el usuario es de un tipo aceptable
		if (!$stp || empty($stp) ||
			!(($stp == 'super') || ($stp == 'director'))) {
			// Se salva accion
			$this->salvarAccion(6, '[Intento]');
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}

		// Validar usuario
		if (!$this->validarUsrTipo($sse, $stp)) {
			// Se salva accion
			$this->salvarAccion(6, '[Intento]');
			// Redireccionar al index
      		header ("Location: " . base_url('/index.php') );
      		return;
		}

		// Se salva accion
		$this->salvarAccion(6, '');

		// Conectar base de datos
		$dbtecint = $this->load->database('tecint', true);
		// Consultar datos con base en la fecha actual
		$dbtecint->select('t_p.reg, t_p.patron, t_p.direccion, t_p.tels');
		$dbtecint->from('patrocinador AS t_p');
		$dbtecint->order_by("t_p.patron", "asc");
		// Obtener resultado de selección
		$miQuery = $dbtecint->get();
		// Cerrar conexión a db
		$dbtecint->close();
		// Componer datos
		foreach($miQuery->result() as $row) {
			if ($row->tels && !empty($row->tels)) {
				$mTels =  explode ('|', $row->tels);
				$row->tels = '';
				foreach($mTels as $tel) {
					if ($row->tels && !empty($row->tels)) {
						if ($tel && !empty($tel)) {
							$row->tels .= (', '.$tel);
						}
					} else {
						$row->tels = $tel;
					}
				}
			} else {
				$row->tels = 'S/N';
			}
		}
		// Devolver datos en formato JSON
		return ($this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(
				json_encode($miQuery->result())
			));
	}

}

?>