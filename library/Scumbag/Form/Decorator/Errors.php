<?php
/**
 * 
 * decorador para modificar la apariencia por default del 
 * decorador de errores
 * con solo llamarlo al final como "Errors" se sustituye por el decorator por default
 * @author ricardo
 *
 */
class Scumbag_Form_Decorator_Errors extends Zend_Form_Decorator_Abstract{
 public function render($content = '')
    {
        $output = '<div class="btn btn-danger">El valor que proporcionó no es válido;
            please try again</div>';
 
        $placement = $this->getPlacement();
        $separator = $this->getSeparator();
 
        switch ($placement) {
            case 'PREPEND':
                return $output . $separator . $content;
            case 'APPEND':
            default:
                return $content . $separator . $output;
        }
    }
}