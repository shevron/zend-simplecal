<?php

class SimpleCal_Model_Email_Invitation extends SimpleCal_Model_Email_Abstract{
    const MAGIC_PASSKEY_SEED = '12wefrguygtf';
    const VIEW_SCRIPT_PATH = 'emails/invite.phtml';
    
    const INVITATION_URL_MODULE = 'default';
    const INVITATION_URL_CONTROLLER = 'invite';
    const INVITATION_URL_ACTION = 'index';
    
    private $_eventId;
    private $_email;
    
    private $_view;
    
    public function __construct($eventId, $email) {
        $this->_eventId = (int) $eventId;
        
        $email = trim($email);
        $validator = new Zend_Validate_EmailAddress();

        if (!$validator->isValid($email)) throw new SimpleCal_Exception('Not a valid email address for creating an invitation');
        
        $this->_email = $email;
        
        // TODO: We don't need a Zend_View object just because of ViewHelper in getInviteUrl()
        $this->_view = new Zend_View(array('basePath' => APPLICATION_PATH . '/views'));
        
        $this->assignToView('inviteUrl', $this->getInviteUrl());
    }
    
    private function getPasskey() {
        return urlencode(md5(base64_encode($this->_email + self::MAGIC_PASSKEY_SEED)));
    }
    
    private function getInviteUrl() {
        $invitePath = $this->_view->url(array(
        	'action' => self::INVITATION_URL_ACTION,
        	'controller' => self::INVITATION_URL_MODULE,
        	'module' => self::INVITATION_URL_MODULE,
            'passkey'=> $this->getPasskey(),
            'event_id' => $this->_eventId));
            
        return $this->_view->baseUrl($invitePath);
    }
    
    public function getSubject() {
        return 'Your Invitation';
    } 
    
    public function getViewScript() {
        return self::VIEW_SCRIPT_PATH;
    } 
    
    public function getTo() {
        return $this->_email;
    }
}