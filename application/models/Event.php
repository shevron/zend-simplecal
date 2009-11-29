<?php

class SimpleCal_Model_Event
{
    protected $_id;
    
    protected $_startTime;
    
    protected $_endTime;
    
    protected $_title;
    
    protected $_description;
    
    protected $_flags = 0;
    
    public function __construct(array $data = array())
    {
        if (isset($data['id'])) $this->_id = $data['id'];
        if (isset($data['start_time'])) $this->_startTime = $data['start_time'];
        if (isset($data['end_time'])) $this->_endTime = $data['end_time'];
        if (isset($data['title'])) $this->_title = $data['title'];
        if (isset($data['description'])) $this->_description = $data['description'];
        if (isset($data['flags'])) $this->_flags = (int) $data['flags'];
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
        return $this->_startTime;
    }

	/**
     * @return the $_endTime
     */
    public function getEndTime()
    {
        return $this->_endTime;
    }

	/**
     * @return the $_title
     */
    public function getTitle()
    {
        return $this->_title;
    }

	/**
     * @return the $_description
     */
    public function getDescription()
    {
        return $this->_description;
    }

	/**
     * @param $_startTime the $_startTime to set
     */
    public function setStartTime($startTime)
    {
        $this->_startTime = $startTime;
    }

	/**
     * @param $_endTime the $_endTime to set
     */
    public function setEndTime($endTime)
    {
        $this->_endTime = $endTime;
    }

	/**
     * @param $_title the $_title to set
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

	/**
     * @param $_description the $_description to set
     */
    public function setDescription ($description)
    {
        $this->_description = $description;
    }
}
