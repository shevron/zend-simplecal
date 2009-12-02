<?php

abstract class SimpleCal_Model_Calendar
{
    protected $_startTime;
    
    protected $_endTime;
    
    protected $_events = null;
    
    public function getStartTime()
    {
        return $this->_startTime;
    }
    
    public function getEndTime()
    {
        return $this->_endTime;
    }
    
    protected function _loadEvents()
    {
        if ($this->_events === null) {
            $events = SimpleCal_Model_Event::findEventsByTime(
                $this->_startTime, $this->_endTime
            );
            
            foreach($events as $event) {
                $this->_loadEvent($event);
            }
        }
    }
    
    public function getEvents()
    {
        $this->_loadEvents();
        return $this->_events;
    }
    
    abstract protected function _loadEvent(SimpleCal_Model_Event $event);
}