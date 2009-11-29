<?php

/**
 * DateFormatter helper
 *
 * @uses viewHelper SimpleCal_View_Helper
 */
class SimpleCal_View_Helper_DateFormatter extends Zend_View_Helper_Abstract
{
    protected $_timestamp = null;
    
    public function dateFormatter ($timestamp)
    {
        $this->_timestamp = $timestamp;
        return $this;
    }
    
    public function hour()
    {
        return date("H:i", $this->_timestamp);
    }
}