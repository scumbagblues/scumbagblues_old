<?php

class Default_Model_Login
{
	protected $_table_name_user = 'usuarios';
	
	protected $_identity_column = 'username';
	
	protected $_credential_column = 'password';
	
	public function userLogin($login,$password){
		$db_adapter = Zend_Db_Table_Abstract::getDefaultAdapter();
		
		$auth_adapter = new Zend_Auth_Adapter_DbTable($db_adapter);
 		//Al Zend_Auth se le asignan los atributos para validar el usuario		
		$auth_adapter->setTableName($this->_table_name_user)
    				 ->setIdentityColumn($this->_identity_column)
    				 ->setCredentialColumn($this->_credential_column)
    				 ->setIdentity($login)
    				 ->setCredential($password);
    				 
    	$select = $auth_adapter->getDbSelect();
    	$select->where("activo = 1");
    	
    	$auth 	= Zend_Auth::getInstance();
    	$result = $auth->authenticate($auth_adapter);
		
    	if($result->isValid()){
    		$data = $auth_adapter->getResultRowObject(array('id_usuario','username','nombre','apellidos'
    														,'id_rol'));
																
            $auth->getStorage()->write($data);
    	}else{
    		
    	}
    	
    	return $result;
	}

}

