<?php
/**
 * 
 * Clase para saber si el usuario esta en session y
 * obtener sus datos
 * @author ricardo
 *
 */

class Default_View_Helper_SesionUsuario extends Zend_View_Helper_Abstract{

	public function sesionUsuario(){
		$usuario_session 		= Zend_Auth::getInstance();
		$html_usuario_session 	= '';
		
		if ($usuario_session->hasIdentity()){
			$username = $usuario_session->getIdentity()->username;
			$html_usuario_session .= "<p class='navbar-text pull-right'>Logged as <a href='#'>" . $username ."</a></p>";
		}else{
			$html_usuario_session .= "<p class='navbar-text pull-right'>
										  <a href='" . $this->view->url(array('module' => 'Default' ,'controller'=>'login', 'action'=>'index')) ."'>
										  	Iniciar Sesión
										  </a>
									  </p>";
		}
		
		return $html_usuario_session;
	}
}