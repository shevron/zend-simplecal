<?php

class SimpleCal_View_Helper_RenderMonthCalendar extends Zend_View_Helper_Abstract
{
    static protected $_wdays = array(
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
    );
    
    public function renderMonthCalendar(SimpleCal_Model_Calendar_Month $cal, $startDay = 0)
    {
        $day = $cal->getFirstWeekStartTime($startDay);
        $lastDay = $cal->getLastWeekEndTime($startDay);
                 
        $inMonth = false;

        $html = '<table class="monthCalendar">';
        
        // Draw weekdays header
        $html .= '<tr class="weekdays">';
        for ($i = 0; $i < 7; $i++) {
            $html .= '<td>' . self::$_wdays[($startDay + $i) % 7] . '</td>';
        }
        $html .= '</tr>';
        
        while ($day <= $lastDay) {
            
            $html .= '<tr class="week">';
            
            for($wDay = 0; $wDay < 7; $wDay++) {
                if ($day >= $cal->getEndTime()) {
                    $inMonth = false; 
                } elseif ($day >= $cal->getStartTime()) {
                    $inMonth = true;
                }
                
                $class = ($inMonth ? '' : ' out-of-scope');
                if ($this->_isToday($day)) $class .= ' today';
                
                $html .= '<td class="day' . $class . '">' . 
                    date('j', $day) . '</td>';
                
                $day += 24 * 3600;
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        return $html;
    }
    
    /**
     * Check if the timestamp provided is for today at 00:00
     * 
     * @param  integer $day
     * @return boolean
     */
    private function _isToday($day)
    {
        $now = $_SERVER['REQUEST_TIME'];
        return ($now >= $day && $now <= ($day + 24 * 3600 - 1));
    }
}