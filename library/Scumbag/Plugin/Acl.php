<?php
/**
 * Clase ACL para otorgar y validar los permisos del usuario en
 * session
 * @author rcortes
 * @package Scumbag_Acl
 */

class Scumbag_Plugin_Acl extends Zend_Controller_Plugin_Abstract{
	
	protected $_auth;
	
	
	
	const DEFAULT_ROLE = 'Guest';
	
	public function __construct() {
		$this->_auth = Zend_Auth::getInstance ();		
	}
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		
		/*
		if ($this->_auth->hasIdentity ()) {
				$id_role = $this->_auth->getIdentity ()->id_rol;
			} else {
				$id_role = 1; //Por default este es el tipo de usuario
			}
			
			$role = $this->_getRole ( $id_role );
			
		    	// create new acl object
	        $acl = new Zend_Acl();
	 
	        // define resources. typically there are
	        // only four resources from the CRUD functionality
	        // but there can be added more resources
	        $acl->addResource(new Zend_Acl_Resource('Default:index'))
	        	->addResource(new Zend_Acl_Resource('Default:login'))
	            ->addResource(new Zend_Acl_Resource('Admin:index'));

	 
	        // define roles
	        $acl->addRole(new Zend_Acl_Role('Guest'))
	            ->addRole(new Zend_Acl_Role('Admin'));
	 
	        // define privileges
	        $acl->allow('Guest','Default:index','index')
	            ->allow('Guest','Default:login','index')
	        	//->allow('guest','Default:Error','error')
	            ->allow('Admin');
	           
	            
	            
	        // setup acl in the registry for more
	        Zend_Registry::set('acl', $acl);
	 		//Zend_Debug::dump($acl->getResources());die;
	 		
	        // check permissions
	        
	        $sController 	= $request->getControllerName();
			$sModule 		= $request->getModuleName();
 
			$sResource = $sModule . ':' . $sController;
	        
			//Zend_Debug::dump($acl->isAllowed('guest', $sResource));die;
	        
	        if (!$acl->isAllowed($role, $sResource,'index')) {
	        	$request->setModuleName('Default');
	            $request->setControllerName('Error');
	            $request->setActionName('error');
	            
	            
	        }
		*/
		
		
		$db = new Zend_Db_Select(Zend_Db_Table::getDefaultAdapter());
		//Si esta autenticado
		if ($this->_auth->hasIdentity ()) {
			$id_role = $this->_auth->getIdentity ()->id_rol;
		} else {
			$id_role = 1; //Por default este es el tipo de usuario
		}
		
		try {
			$config_modules = Zend_Registry::get ( 'modules_config' );
		} catch ( Exception $e ) {
			$config_modules = new Zend_Config_Ini ( APPLICATION_PATH . '/configs/modules.ini', 'modules' );
			Zend_Registry::set ( 'modules_config', $config_modules );
		}
		$acl 		= new Zend_Acl();
		$this->_setResources ( $acl, $config_modules );
		$this->_setRoles ($acl);
		$role 		= $this->_getRole ($id_role );
		$permisos	= $this->_getPermissions($id_role);
		
		$permisos_array = explode(',', $permisos);
		//var_dump($permisos_array);die;
		
		//TODO: iterar permisos para los roles dados
		
		if ($role == self::DEFAULT_ROLE) {
			$acl->allow( self::DEFAULT_ROLE, 'Default:login')
				->allow( self::DEFAULT_ROLE, 'Default:index')
				->allow( self::DEFAULT_ROLE, 'Default:error');
				
		}
		$acl->allow( 'Admin' );
		
		Zend_Registry::set('acl', $acl);
		
		// check permissions
		$controller = $request->getControllerName ();
		$module = $request->getModuleName ();
		
		$resource = $module . ':' . $controller;
		//var_dump($acl,$role, $resource,$acl->isAllowed ( $role, $resource)); die;
		
		if (! $acl->isAllowed ( $role, $resource,'index')) {
			
			$request->setModuleName ( 'Default' );
			$request->setControllerName ( 'error' );
			$request->setActionName ( 'error' );
		
		}
		//die ();
	
	}
	
	/**
	 * 
	 * Método para agregar los modulos - controladores del sistema
	 * que estan en configs/modules.ini
	 * @param $config_modules
	 */
	protected function _setResources(Zend_Acl $acl, $config_modules) {
		try {
			foreach ( $config_modules->modules as $modulo => $mod ) {
				foreach ( $config_modules->modules->$modulo as $controlador => $acciones ) {
					//var_dump($modulo,$controlador,$config_modules->modules->$modulo->$controlador->toArray());
					$modulo_controlador = $modulo . ':' . $controlador;
					//Se asignan los resources para los que se dara permisos
					$acl->addResource ( new Zend_Acl_Resource ( $modulo_controlador ) );
				}
			}
		} catch ( Exception $e ) {
			//no se agregan los resources porque ya estan agregados
		}
	
	}
	
	/**
	 * 
	 * Método para agregar los roles que habra en el sistema
	 * Pueden ser cuantos roles sean la unica condición es
	 * que exista una tabla llamada "roles"
	 * con los campos "id_rol" y "role_name"
	 */
	protected function _setRoles($acl) {
		
		try {
			$result_roles = Zend_Registry::get ( 'result_roles' );
		
		} catch ( Exception $e ) {
			//Se obtiene el adaptador por default de la BD
			//Se realiza el query
			$db = new Zend_Db_Select(Zend_Db_Table::getDefaultAdapter());
			$select = $db->from ( 'roles' );
			$statement = $select->query ();
			//Se obtiene el resultado
			$result_roles = $statement->fetchAll ();
			Zend_Registry::set ( 'result_roles', $result_roles );
		}
		
		foreach ( $result_roles as $key_role => $value_role ) {
			//Se agregan los roles del sistema
			$acl->addRole ( new Zend_Acl_Role ( $value_role ['role_name'] ) );
		}
		
		//$select->reset();
	}
	
	/**
	 * 
	 * Método para obtener el nombre del rol del usuario en 
	 * session
	 * @param string $id_role
	 */
	protected function _getRole($id_role){
		
		$db = new Zend_Db_Select(Zend_Db_Table::getDefaultAdapter());
		$select = $db->from('roles','role_name')
					 ->where('id_rol = ?',$id_role);
		//echo $select->__toString();
		$statement = $select->query();
		$role_name = $statement->fetchColumn();

		return $role_name;
	}
	
	protected function _getPermissions($id_role){
		
		$db = new Zend_Db_Select(Zend_Db_Table::getDefaultAdapter());
		$select = $db->from('permisos','permisos_nombre')
							->where('id_rol = ?',$id_role);
		//echo $select->__toString();					
		$statement	= $select->query();

		$permisos	= $statement->fetchColumn();
		
		//$select->reset();
		
		return $permisos;
	}
}