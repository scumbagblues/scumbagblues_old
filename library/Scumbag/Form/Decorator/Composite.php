<?php
/**
 * 
 * Decorador compuesto:
 * modifica la forma en que se ven:
 * -Inputs
 * -Labels
 * -Errores
 * -Descripciones
 * @author ricardo
 *
 */
class Scumbag_Form_Decorator_Composite extends Zend_Form_Decorator_Abstract {

	protected $_class_label = 'control-label';
	
	
	//Crea el html completo de etiqueta, input y errores
	public function buildLabelInput() {
		$element = $this->getElement ();
		$label = '';
		$html_label_input = "<div class='control-group'>";
		
		//Si el elemento es obligatorio antepone un asterisco
		if ($element->isRequired ()) {
			$label .= '*';
		}
		//Se obtiene la etiqueta
		$label .= $element->getLabel ();
		//Si se tiene traducción se aplica
		if ($translator = $element->getTranslator ()) {
			$label .= $translator->translate ( $label );
		}
		
		$label .= ':';
		//Se obtiene toda la etiqueta label
		$html_label = $element->getView ()->formLabel ( $element->getName (), $label, array ('class' => $this->_class_label ) );
		//Se agrega la etiqueta label que se acaba de generar
		$html_label_input .= $html_label;
		$html_label_input .= "<div class='controls'>";
		//Para agregar una clase al input se hace desde la forma ex: Default_Form_Login
		//Se construye el input
		$input = $this->buildInput ();
		//Se construye el msj de error
		$error_message = $this->buildErrors ();
		
		$html_label_input .= $input . $error_message;
		
		$html_label_input .= '</div>';
		
		return $html_label_input;
	}
	
	//Crea el html del input
	public function buildInput() {
		$element = $this->getElement ();//Se obtiene el Zend_Form_Element_Text
		$helper = $element->helper;//Se obtiene el helper que crea el input "formText"
		return $element->getView ()->$helper ( $element->getName (), $element->getValue (),  $element->options );
	}
	
	//Crea el html de los mensajes de error
	public function buildErrors() {
		$element = $this->getElement ();
		$messages = $element->getMessages ();
		if (empty ( $messages )) {
			return '';
		}
		//Se settea los elementos html de inicio y fin para el html de errores
		$form_errors_helper = $element->getView()->getHelper('formErrors');
		$form_errors_helper	->setElementStart('<p>') 
               				->setElementSeparator('<br />') 
               				->setElementEnd('</p>'); 

		return $element->getView ()->formErrors ( $messages );
	}
	
	public function buildSubmit(){
		$element 	= $this->getElement();
		$messages 	= $element->getMessages();
		
		if (empty($messages)){
			return '';
		}

		return $element->getView()->formSubmit($messages);
	}
	
	public function buildDescription() {
		$element = $this->getElement ();
		$desc = $element->getDescription ();
		if (empty ( $desc )) {
			return '';
		}
		return '<div class="description">' . $desc . '</div>';
	}
	
	public function render($content) {
		$element = $this->getElement ();
		if (! $element instanceof Zend_Form_Element) {
			return $content;
		}
		if (null === $element->getView ()) {
			return $content;
		}
		
		$separator = $this->getSeparator ();
		$placement = $this->getPlacement ();
		$html_input= $this->buildLabelInput();
		
		$desc = $this->buildDescription ();
		
		$output = '<div class="form element">' . $html_input . $desc . '</div>';
		
		switch ($placement) {
			case (self::PREPEND) :
				return $output . $separator . $content;
			case (self::APPEND) :
			default :
				return $content . $separator . $output;
		}
	}
}