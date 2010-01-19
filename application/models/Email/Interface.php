<?php

interface SimpleCal_Model_Email_Interface
{    
    public function getTo();

    public function getSubject();
    
    public function getViewScript();
    
    public function assignToView($spec, $value = null);
    
    public function getViewAssignments();
}
