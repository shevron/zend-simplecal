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
            $html .= '<th>' . self::$_wdays[($startDay + $i) % 7] . '</th>';
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
                
                $dayDate = date("Y-m-d", $day);
                $mday = date('j', $day);
                $events = $cal->getEventsForDay($mday);                
                
                $class = ($inMonth ? '' : ' out-of-scope');
                if ($this->_isToday($day)) $class .= ' today';
                if (! empty($events)) $class .= ' has-events';
                
                $html .= '<td class="day' . $class . '" id="cal-day-' . $dayDate . 
                    '"><span class="day-title">' . $mday . '</span><span class="day-add">' . 
                    "<a href=\"{$this->view->baseUrl}/event/create/date/{$dayDate}\">+</a></span>"; 
                    
                if (! empty($events)) {
                    $html .= '<ul>';
                    foreach($events as $event) { /* @var $event SimpleCal_Model_Event */
                        $html .= '<li>' .
                            $this->view->dateFormatter($event->getStartTime())->hour() . " " . 
                            htmlspecialchars($event->getTitle()) . '</li>';
                    }
                    $html .= '</ul>';
                }
                    
                $html .= '</td>';
                
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