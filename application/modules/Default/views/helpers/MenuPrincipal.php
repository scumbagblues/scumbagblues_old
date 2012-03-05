<?php

class Default_View_Helper_MenuPrincipal extends Zend_View_Helper_Abstract{
	
	public function menuPrincipal(){
		
		$usuario_session = Zend_Auth::getInstance();
		
		$html_menu_principal = "<li class=\"active\"><a href=\"#\">Home</a></li>
              					<li><a href=\"#about\">Galer&iacute;a de Conciertos</a></li>
              					<li><a href=\"#reviews\">Reviews</a></li>
              					<li><a href=\"#contact\">Contacto</a></li>";
		
		if ($usuario_session->hasIdentity()){
			$html_menu_principal .= "<li><a href='" . $this->view->url(array('module' => 'Default', 'controller' => 'login', 'action' => 'logout')) . "'>Finalizar Sesión</a>";
		}
		
		return $html_menu_principal;
								
	}
}