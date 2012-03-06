<?php

class Default_LoginController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	/*
    	$front_controller = Zend_Controller_Front::getInstance();
    	$login_plugin = $front_controller->getPlugin('Scumbag_Plugin_Auth');
    	$front_controller->unregisterPlugin($login_plugin);*/
    	
    	$frontController = $this->getFrontController();
		$plugin = $frontController->getPlugin('Scumbag_Plugin_Auth');
		$frontController->unregisterPlugin($plugin);

    }

    public function indexAction()
    {
        
    	
    	
    	// action body
        $form_login = new Default_Form_Login();
       
        $this->view->form = $form_login;
        
        if ($this->getRequest()->isPost()){
        	$form_login_data = $this->getRequest()->getPost();
        	if ($form_login->isValid($form_login_data)) {
        		$login = $form_login->getValue('user');
				$password = $form_login->getValue('password');
				
				//Se obtiene el adaptador de la bd
				$auth_user_adapter = new Default_Model_Login();
				$auth_user = $auth_user_adapter->userLogin($login, $password);
				
				if($auth_user->isValid()){
					//Se manda llamar al plugin de auth para insertar la fecha_ultima_entrada
					//cuando ingresa al sistema y esot se debe realizar
					//solo UNA vez!!
					//$inser_data = new Ford_Plugin_Auth();
					//$inser_data->insertDataUser();
					$this->_redirect('Admin/index/index');
					//$this->_forward('index','index');
				} else{
					
					switch ($auth_user->getCode()){
						
							//En caso de que el login sea incorrecto
							case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND: 
								$mensaje_error = 'El login es incorrecto o el usuario esta inactivo';
								break;

							//En caso de que el password sea incorrecto	
							case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
								$mensaje_error = 'El password es incorrecto';
								break;

							//Falla de otro tipo	
							 case Zend_Auth_Result::FAILURE:
							 	$mensaje_error = 'Se encontro algun error en tus datos';
							 	break;	
							
							//Mensaje de error generico			
							default: $mensaje_error = 'Se encontro algun error en tus datos vueve a intentarlo';	
						}
						
						$this->view->error_login = $mensaje_error;
				}
        	} else{
        	
        		$form_login->populate($form_login_data);
        	}
        	
        }else{
        	//No se envio por POST
        }
    }
    
	/**
	 * 
	 * Método para cerrar session
	 */
 	public function logoutAction(){
 		
        Zend_Auth::getInstance()->clearIdentity();
        //Borramos el namespace de la session donde se guardaron
        //los datos del usuario logueado
        //Zend_Session::namespaceUnset('user_data');
        //unset($user_data->data);
        $this->_forward('index','login'); // back to login page
    }


}

