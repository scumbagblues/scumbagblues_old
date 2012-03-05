<?php

class Default_Form_Login extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    	$this->setName('frmLogin');
    	$this->_attribs = array('class' => 'form-horizontal'); //Atributos para la forma
    	
    	//Se agrega la ruta del decorador
    	$this->addElementPrefixPaths(array(
            'decorator' => array('Scumbag_Form_Decorator' => 'Scumbag/Form/Decorator/'),
        ));
    	
    	$this->addElement('text','user',array('required' 	=> true, 
    										  'label' 		=> 'User',
    										  //'class'	    => 'campos_text',
    										  //'decorators'   => array(array('DivTag'),array(Errors,array('class' => 'formErrors', 'placement' => 'prepend'))),
    										  'decorators'  => array('Composite'),
    										  'filters'    	 => array('StringTrim','StripTags')));
    	
    	$this->addElement('password','password',array('required'=> true, 
    											      'label' 	=> 'Password',
    												  'decorators'  => array('Composite')));
    												  //'decorators'   => array(array('DivTag'),array(Errors,array('class' => 'formErrors', 'placement' => 'prepend')))));
    	
    	$this->addElement('submit', 'Enviar', array('class' => 'btn btn-primary-scumbag btn-large'));
    }


}

