<?php
class SimpleCal_Model_Email_Abstract implements SimpleCal_Model_Email_Interface {
    protected $viewAssignments = array();
    
    final public function assignToView($spec, $value = null) {
        $this->viewAssignments[] = array($spec, $value);    
    }
    
    final public function getViewAssignments() {
        return $this->viewAssignments;
    }
    
    public function getTo() {
        throw new SimpleCal_Exception('Has to be implemented by inheriting class');    
    }

    public function getSubject() {
        throw new SimpleCal_Exception('Has to be implemented by inheriting class');
    }
    
    public function getViewScript() {
        throw new SimpleCal_Exception('Has to be implemented by inheriting class');
    }
}