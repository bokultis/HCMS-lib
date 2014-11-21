<?php
/**
 * Error messages Rendering
 *
 * @package HCMS
 * @subpackage View
 * @copyright Horisen
 * @author milan
 *
 */
class HCMS_View_Helper_ErrorMessages {

    private $_view;
    
    public function setView($view) {
        $this->_view = $view;
    }

    /**
     * Display Error Array as messages.
     *
     * @param  array $fieldErrors
     * @return string
     */
    public function errorMessages($fieldErrors,$class = "error") {
        if(isset ($fieldErrors) && count($fieldErrors)) {
            $result = "<ul class=\"$class\">";
            foreach ($fieldErrors as $error => $message) {
                if(is_array($message)){
                    foreach($message as $key => $msg){
                        $result .= '<li>' . htmlspecialchars($msg) . '</li>';
                    }
                }
                else{
                    $result .= '<li>' . htmlspecialchars($message) . '</li>';
                }
            }
            $result .= '</ul>';
            return $result;
        }
    }
}
?>
