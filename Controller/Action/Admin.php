<?php
/**
 * Cms admin action controller - base for all admin controllers
 *
 * @package    HCMS
 * @subpackage Controller
 * @copyright  Horisen
 */

class HCMS_Controller_Action_Admin extends HCMS_Controller_Action_Cms {

    protected $_checkAuth = true;

    protected $_authResourse = 'admin';
    protected $_authPrivilege = 'access';
    
    /**
     *
     * @var Auth_Model_User
     */
    protected $_admin;
    
    protected $_versionInfo;

    /**
     *
     * @var Application_Model_Module
     */
    protected $_module = null;

    protected $_isFrontEnd = false;

    public function init() {
        //load acl
        $aclLoader = HCMS_Acl_Loader::getInstance();
        $aclLoader->load();
        
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_admin = null;
        }
        else{
            $this->_admin = Zend_Auth::getInstance()->getIdentity();
            $aclLoader->setCurrentRoleCode($aclLoader->getRoleCode($this->_admin->get_role_id()));
        }
        $this->view->admin = $this->_admin;
        if($this->_checkAuth){
            $this->_checkAuthorization();
        }
        //set ACL object for Zend_Navigation
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($aclLoader->getAcl());
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($aclLoader->getCurrentRoleCode());

        $this->_initVersionInfo();
        $this->_module = new Application_Model_Module();
        if(Application_Model_ModuleMapper::getInstance()->findByCode($this->getRequest()->getModuleName(), $this->_module)){
            $this->view->moduleSettings = $this->_module->get_settings();
        }
        parent::init();        
    }

    /**
     * Check authorization
     */
    protected function _checkAuthorization() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            //redirect to login page
            $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'login', 'module' => 'admin')));
        }
        $aclLoader = HCMS_Acl_Loader::getInstance();
        //check permission
        if(!$aclLoader->getAcl()->isAllowed($aclLoader->getCurrentRoleCode(), $this->_authResourse, $this->_authPrivilege)){
            //redirect to login page
            $this->_redirect($this->view->url(array('controller' => 'index', 'action' => 'login', 'module' => 'admin')));
            throw new Zend_Controller_Action_Exception("You are not allowed to access this page",403);
        }
    }

    protected function _initVersionInfo(){
        if(file_exists(APPLICATION_PATH . "/version.json")){
            $this->_versionInfo = json_decode(file_get_contents(APPLICATION_PATH . "/version.json"), true);
        }
        else{
            $this->_versionInfo = array();
        }
        $this->view->versionInfo = $this->_versionInfo;
    }

    protected function _initLayout(){
        $layout = Zend_Layout::getMvcInstance();
        $layout ->setLayoutPath(APPLICATION_PATH . '/modules/admin/layouts')
                ->setLayout('admin');
        $this->view->headScript()->prependFile('/' . CURR_LANG . '/default/lang-js');
    }

    protected function _initMenus(){
        $this->view->navigation(
                new Zend_Navigation(require APPLICATION_PATH . '/modules/admin/configs/navigation.php')
        );
    }
}