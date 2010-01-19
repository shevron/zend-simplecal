<?php

class SimpleCal_Controller_Action_Helper_JqEmail extends Zend_Controller_Action_Helper_Abstract
{
    const VALIDATION_PASSWORD = '238ddae81c627af85737fa82cebfe885';
    
    /**
     * @var Zend_View_Interface
     */
    private $_view;
    
    private $_emailContent;

    public function __construct()
    {
        $this->_view = new Zend_View(array('basePath' => APPLICATION_PATH . '/views'));
    }
    
    public function getValidationPassword() {
        return self::VALIDATION_PASSWORD;
    }
    
    private function assignToView($spec, $value = null) {
        $this->_view->assign($spec, $value);
    }
    
    private function renderBody($script) {
        return $this->_view->render($script);
    }

    public function getParamArr() {
        foreach ((array) $this->_emailContent->getViewAssignments() as $assign) {
            $this->_view->assign($assign[0], $assign[1]);
        }
        
        $arr = array(
        	'password' => self::VALIDATION_PASSWORD,
        	'to' => $this->_emailContent->getTo(),
            'subject' => $this->_emailContent->getSubject(),
            'body' => $this->renderBody($this->_emailContent->getViewScript()));
        
        return $arr;    
    }
    
    public function direct(SimpleCal_Model_Email_Interface $emailContent)
    {
        $this->_emailContent = $emailContent;
        return $this->getParamArr();
    }
}
