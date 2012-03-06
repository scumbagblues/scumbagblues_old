<?php

class Scumbag_Form_Decorator_Submit extends Zend_Form_Decorator_Abstract{
	
	protected $_format = " <div class='form-actions'>
							  <div class='center'>
            					<input type='submit' class='btn btn-primary-scumbag btn-large' value=\"%s\" id=\"%s\" name=\"%s\">
          				   	  </div>
            			   </div>";
 
    public function render($content)
    {
    	
        $element = $this->getElement();
        $name    = htmlentities($element->getFullyQualifiedName());
        $label   = htmlentities($element->getLabel());
        $id      = htmlentities($element->getId());
        $value   = htmlentities($element->getValue());
 		
        $markup  = sprintf($this->_format, $name, $label, $id, $name, $value);
  
        return $markup;
    }   
}