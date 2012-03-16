<?php

class Admin_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        
    	$user_model = new Admin_Model_Users();
    	//Zend_Debug::dump($user_model);die;   	
    	$result = $user_model-> fetchAll();
    	
    	if( isset($result)){
    		$paginator = Zend_Paginator::factory($result);
    		$paginator->setItemCountPerPage('2');
    		$paginator->setCurrentPageNumber($this->_getParam('page'));
    		$this->view->paginator = $paginator;
    		Zend_Paginator::setDefaultScrollingStyle('Sliding');
    		Zend_View_Helper_PaginationControl::setDefaultViewPartial('index/pagination.phtml');
    	}
    }


}

