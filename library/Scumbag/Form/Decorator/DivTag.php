<?php
/**
 * 
 * Decorador para cambiar la forma en que se mostraban los inputs y label
 * @author ricardo
 *
 */
class Scumbag_Form_Decorator_DivTag extends Zend_Form_Decorator_Abstract{
	
	protected $_format = " <div class='control-group'>
						   	<label class='control-label' for=\"%s\">%s</label>
						   	<div class='controls'>
						   		<input id=\"%s\" name=\"%s\" type=\"text\" value=\"%s\"/>
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