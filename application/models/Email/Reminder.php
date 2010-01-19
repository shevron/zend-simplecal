<?php

class SimpleCal_Model_Email_Reminder extends SimpleCal_Model_Email_Abstract{
    const VIEW_SCRIPT_PATH = 'emails/remind.phtml';
    
    protected $_email;
    
    public function __construct($email) {
        $email = trim($email);
        $validator = new Zend_Validate_EmailAddress();

        if (!$validator->isValid($email)) throw new SimpleCal_Exception('Not a valid email address for creating an invitation');
        
        $this->_email = $email;
    }
    
    public function getSubject() {
        return 'Reminder';
    } 
    
    public function getViewScript() {
        return self::VIEW_SCRIPT_PATH;
    } 
    
    public function getTo() {
        return $this->_email;
    } 
}