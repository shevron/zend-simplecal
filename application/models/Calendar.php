<?php

abstract class SimpleCal_Model_Calendar
{
    protected $_startTime;
    
    protected $_endTime;
    
    public function getStartTime()
    {
        return $this->_startTime;
    }
    
    public function getEndTime()
    {
        return $this->_endTime;
    }
    
    public function loadEvents()
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                     ->from('events')
                     ->where('start_time >= ' . $this->_startTime)
                     ->where('start_time <= ' . $this->_endTime)
                     ->order('start_time');
                     
        $result = $select->query();
        var_dump($result);
    }
}