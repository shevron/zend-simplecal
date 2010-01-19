<?php

class SimpleCal_Model_Event
{
    const WHOLE_DAY = 1;
     
    protected $_id;
    
    protected $_data = array(
        'start_time'  => null,
        'end_time'    => null,
        'title'       => null,
        'reminder'    => null,
        'description' => null,
        'invite'      => null,
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
    
    public function getStartTimeMonth() {
        return date("Y/m", $this->_data['start_time']);
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
     * @return string
     */
    public function getInvite()
    {
        return $this->_data['invite'];
    }
    
    public function getInvitationEmails() {
        return explode(',', $this->_data['invite']);
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
        if (isset($this->_id) && $this->_id > 0) {
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
    
    public function delete() {
        $table = $this->_getTable();
        $where = $table->getAdapter()->quoteInto('id = ?', $this->_id);
        $table->delete($where);
        $this->_id = null;
    }
    
    public function getFormContainer() {
        $data = $this->_data;

        $data['date'] = date('Y-m-d', $data['start_time']);
        $data['time'] = date('\TH:i:s', $data['start_time']);
        
        unset($data['start_time']);
        unset($data['end_time']);
        unset($data['flags']);
        
        return $data;
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
    
    public function getReminderTime() {
        // TODO: reminder time should not be before now!
        return date('Y-m-d H:i:s', $this->_data['start_time'] - (60 * 60 * $this->_data['reminder']));
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
    
    /**
     * Returns an instance of SimpleCal_Model_Event, event id has to be provided
     * 
     * @param int $id EventId (Primary key)
     * 
     * @return SimpleCal_Model_Event 
     */
    public static function getInstanceById($id) {
         $table = new SimpleCal_Model_DbTable_Events();
         $rowset = $table->find($id);
         if (count($rowset) < 1) throw new SimpleCal_Exception("Unable to find Event for id [$id]");
         $row = current($rowset);
         return new self($row[0]);   
    }
    
    public static function getInstanceByForm(SimpleCal_Form_Event $form) {
        $data = $form->getValues();
        $startTime = strtotime($data['date'] . " " . $data['time']);

        $data['start_time'] = $startTime;
        $data['end_time'] = $startTime + 1800; // TODO: Implement this!    
        
        return new self($data);
    }
}
