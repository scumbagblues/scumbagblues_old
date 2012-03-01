<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	
	protected function _initJquery() {

		$view = new Zend_View($this->getOptions()); //get the view object
		//add the jquery view helper path into your project
		$view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
		
		$base_url = substr($_SERVER['PHP_SELF'], 0, -9);
		
		//jquery lib includes here (default loads from google CDN)
		$view->jQuery()	->enable()//enable jquery ; ->setCdnSsl(true) if need to load from ssl location
						->setLocalPath($base_url .'js/jquery/jquery-1.7.1.min.js')
						->setUiLocalPath($base_url .'js/jquery/jquery-ui-1.8.13.custom.min.js')				
		//->setVersion('1.5')//jQuery version, automatically 1.5 = 1.5.latest
						//->setUiVersion('1.8')//jQuery UI version, automatically 1.8 = 1.8.latest
						->uiEnable();//enable ui

	}

}

