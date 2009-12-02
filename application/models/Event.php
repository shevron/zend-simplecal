<?php

class SimpleCal_Model_Event
{
    const WHOLE_DAY = 1;
     
    protected $_id;
    
    protected $_data = array(
        'start_time'  => null,
        'end_time'    => null,
        'title'       => null,
        'description' => null,
        'flags'       => 0
    );
    
    /**
     * Table gateway object instance
     * 
     * @var SimpleCal_Model_DbTable_Events
     */
    protected $_table = null;
    
    public function __construct(array $data = array())
    {
        if (isset($data['id'])) $this->_id = $data['id'];
        
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->_data)) {
                $this->_data[$key] = $value;
            }
        }
    }
    
	/**
     * @return the $_id
     */
    public function getId()
    {
        return $this->_id;
    }

	/**
     * @return the $_startTime
     */
    public function getStartTime()
    {
        return $this->_data['start_time'];
    }

	/**
     * @return the $_endTime
     */
    public function getEndTime()
    {
        return $this->_data['end_time'];
    }

	/**
     * @return the $_title
     */
    public function getTitle()
    {
        return $this->_data['title'];
    }

	/**
     * @return the $_description
     */
    public function getDescription()
    {
        return $this->_data['description'];
    }

	/**
     * @param $_startTime the $_startTime to set
     */
    public function setStartTime($startTime)
    {
        $this->_data['start_time'] = $startTime;
    }

	/**
     * @param $_endTime the $_endTime to set
     */
    public function setEndTime($endTime)
    {
        $this->_data['end_time'] = $endTime;
    }

	/**
     * @param $_title the $_title to set
     */
    public function setTitle($title)
    {
        $this->_data['title'] = $title;
    }

	/**
     * @param $_description the $_description to set
     */
    public function setDescription ($description)
    {
        $this->_data['description'] = $description;
    }
    
    /**
     * Check whether this event is a whole day event
     * 
     * @return boolean
     */
    public function getIsWholeDay()
    {
        return (bool) self::WHOLE_DAY & $this->_data['flags'];
    }

    /**
     * Mark the event as a whole day event (or unmark it)
     * 
     * @param boolean $wholeDay
     */
    public function setIsWholeDay($wholeDay)
    {
        $this->_setFlag(self::WHOLE_DAY, (bool) $wholeDay);
    }
    
    /**
     * 
     */
    public function save()
    {
        if (isset($this->_id)) {
            // Update
            $this->_getTable()->update(
                $this->toArray(), 
                'id = ' . (int) $this->_id
            );
            
        } else {
            // Insert
            $this->_getTable()->insert(
                $this->toArray()
            );
            
            $this->_id = $this->_getTable()->getLastInsertId();
        }
    }
    
    public function toArray($returnId = false)
    {
        return $this->_data;
    }
    
    /**
     * Lazy-instantiate and return the table gateway object
     * 
     * @return SimpleCal_Model_DbTable_Events
     */
    protected function _getTable()
    {
        if ($this->_table === null) {
            $this->_table = new SimpleCal_Model_DbTable_Events();
        }
        
        return $this->_table;
    }
    
    /**
     * Set or unset a flag bit
     * 
     * @param integer $flag
     * @param boolean $status
     */
    protected function _setFlag($flag, $status)
    {
        if ($status) {
            $this->_data['flags'] |= $flag;
        } else {
            $this->_data['flags'] &= ~$flag;
        }
    }

    /**
     * Get list of events between the specified start & end times
     * 
     * @param integer $startTime
     * @param integer $endTime
     */
    static public function findEventsByTime($startTime, $endTime)
    {
        $table = new SimpleCal_Model_DbTable_Events();
        
        $stmt = $table->select()
                      ->where('start_time >= ?')
                      ->where('end_time <= ?')
                      ->order('start_time')
                      ->query(Zend_Db::FETCH_ASSOC, array($startTime, $endTime));
        
        $ret = array();
        while ($data = $stmt->fetch()) {
            $ret[] = new self($data);
        }
        
        return $ret;
    }
}
