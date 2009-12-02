<?php

class SimpleCal_Model_DbTable_Events extends Zend_Db_Table
{
    protected $_name = 'events';

    /**
     * Get the last insert ID of this table
     * 
     * @todo   move this to an abstract parent class
     * @return integer
     */
    public function getLastInsertId()
    {
        return $this->getAdapter()->lastInsertId(
            $this->_name, $this->_primary
        );
    }
}