<?php

class Scumbag_Plugin_Auth extends Zend_Controller_Plugin_Abstract{
	
	protected $_auth;
	
	
	public function __construct(){
		$this->_auth = Zend_Auth::getInstance();	
	}
	
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
		try {
			Zend_Session::start ();
			if ($this->_auth->hasIdentity ()) {
				//Esta autenticado, se realizan acciones de autenticación				
				//Zend_Debug::dump($this->_auth->getIdentity()->role);die;
			} else {
				//no esta autenticado
				//$request->setModuleName ( 'Default' )->setControllerName ( 'index' )->setActionName ( 'index' );
			
			}
		} catch ( Zend_Session_Exception $e ) {
			//session_start();
		}
	}
	
	/**
	 * 
	 * Método para insertar la fecha de ultima entrada al sistema
	 
	 public function insertDataUser(){
		
		$user_table = new User_Model_Users();
		$where = "id_usuario = " . $this->_auth->getIdentity()->id_usuario;
		
		$user_row 	= $user_table->fetchRow($where);
		$user_row->fecha_ultima_entrada = date('Y-m-d h:i:s');
		$user_row->save();
	}
	*/
	
}