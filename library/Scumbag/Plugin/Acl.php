<?php
/**
 * Clase ACL para otorgar y validar los permisos del usuario en
 * session
 * @author rcortes
 * @package Scumbag_Acl
 */

class Scumbag_Plugin_Acl extends Zend_Controller_Plugin_Abstract{
	
	protected $_auth;
	
	public function preDispatch(Zend_Controller_Request_Abstract $request){
		
		$this->_auth = Zend_Auth::getInstance();
		//Si esta autenticado
		if ($this->_auth->hasIdentity()){
			
			$acl = new Zend_Acl();

			try {
				$config_modules = Zend_Registry::get('modules_config');
			}catch (Exception $e){
				$config_modules = new Zend_Config_Ini(APPLICATION_PATH . '/configs/modules.ini','modules');
				$registry = Zend_Registry::getInstance();
				$registry->set('modules_config', $config_modules);
				
			}
			
			
			foreach ($config_modules->modules as $modulos => $mods){
				foreach ($config_modules->modules->$modulos as $acciones_key => $acciones_value){
					var_dump($modulos,$config_modules->modules->$modulos->$acciones_key->toArray(),$acciones_value);
				}
			}
			
			
			die;
		}else{
			//es un usuario del tipo "Guest"
		}
		
	}
}