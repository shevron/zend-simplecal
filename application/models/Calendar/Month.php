<?php

class SimpleCal_Model_Calendar_Month extends SimpleCal_Model_Calendar
{
    protected $_month;
    
    protected $_year;
    
    protected $_startDateInfo = array();
    
    public function __construct($month = null, $year = null)
    {
        if ($month === null) $month = date('n');
        if ($year === null) $year = date('Y');
        
        $this->_startTime = mktime(0, 0, 0, $month, 1, $year);
        $this->_startDateInfo = getdate($this->_startTime);
        
        $this->_month = $this->_startDateInfo['mon'];
        $this->_year  = $this->_startDateInfo['year'];
        
        if ($this->_month == 12) {
           $this->_endTime = mktime(0, 0, 0, 1, 1, $this->_year + 1) - 1;
        } else {
            $this->_endTime = mktime(0, 0, 0, $this->_month + 1, 1, $this->_year) - 1;
        }  
    }
    
    public function getFirstWeekStartTime($startDay = 0)
    {
        if ($startDay < 0 || $startDay > 6) {
            throw new OutOfRangeException("Week start day must be between 0 (Sunday) and 6 (Friday)");
        }
        
        $diffDays = (7 + $this->_startDateInfo['wday'] - $startDay) % 7;
        return $this->_startTime - ($diffDays * 3600 * 24);
    }
    
    public function getLastWeekEndTime($startDay = 0)
    {
        if ($startDay < 0 || $startDay > 6) {
            throw new OutOfRangeException("Week start day must be between 0 (Sunday) and 6 (Friday)");
        }

        $endDateInfo = getdate($this->_endTime);
        $diffDays = ($startDay + 6 - $endDateInfo['wday']) % 7;
        return $this->_endTime + ($diffDays * 3600 * 24);
    }
    
    public function getEventsForDay($day)
    {
        $this->_loadEvents();
        if (isset($this->_events[$day])) {
            return $this->_events[$day];
        } else {
            return array();
        }
    }
    
    protected function _loadEvent(SimpleCal_Model_Event $event)
    {
        $eventDay = date('Y-m-d', $event->getStartTime());
        if (! isset($this->_events[$eventDay])) $this->_events[$eventDay] = array();
        $this->_events[$eventDay][] = $event;
    }
}