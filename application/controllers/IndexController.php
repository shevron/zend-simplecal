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
            /**
             * @todo Filter unsafe input before putting into strtotime() 
             */
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
        
        $this->view->calendar = $cal;
    }
}