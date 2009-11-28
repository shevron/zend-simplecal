<?php

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $month = $this->_getParam('month', null);
        $year  = $this->_getParam('year', null);
        
        $cal = null;
        
        if (($jumpTo = $this->_getParam('jumpto')) !== null) {
            $jumpTo = strtotime($jumpTo);
            if ($jumpTo) {
                $cal = new SimpleCal_Model_Calendar_Month(date("n", $jumpTo), date("Y", $jumpTo));
            } else {
                /**
                 * @todo "Don't know how to jump to..." error message
                 */
            }
        }
        
        if (! $cal) {
            $cal = new SimpleCal_Model_Calendar_Month($month, $year);
        }
        
        $cal->loadEvents();
        
        $this->view->calendar = $cal;
    }
}