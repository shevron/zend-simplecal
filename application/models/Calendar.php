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
            $db = Zend_Db_Table::getDefaultAdapter();
            $select = $db->select()
                         ->from('events')
                         ->where('start_time >= ' . $this->_startTime)
                         ->where('start_time <= ' . $this->_endTime)
                         ->order('start_time');
                         
            $stmt = $select->query();
            
            $this->_events = array();
            while ($row = $stmt->fetch(Zend_Db::FETCH_ASSOC)) {
                $this->_loadEvent(new SimpleCal_Model_Event($row));
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