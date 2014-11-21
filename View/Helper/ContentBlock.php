<?php
/**
 * Theme Path view helper - prefixes theme path
 *
 * @package HCMS
 * @subpackage View
 * @copyright Horisen
 * @author ljuba
 *
 */
class HCMS_View_Helper_ContentBlock extends Zend_View_Helper_Abstract {

    /**
     * Content block view helper - retrieves a content block with certain code
     *
     * @param string $url_id - url_id of the content block
     * @param strin $lang - current language
     * @return string
     */
    private $_application_id = 1;
    
    private $_type = 'contentblock';

    public function contentBlock($url_id, $lang) {
        $block = new Cms_Model_Page();
        Cms_Model_PageMapper::getInstance()->findByUrlId($url_id , $this->_application_id, $block, $lang, $this->_type);
        return $block->get_content();
    }
}