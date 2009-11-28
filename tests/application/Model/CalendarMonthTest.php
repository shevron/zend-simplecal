<?php

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * SimpleCal_Model_Calendar_Month test case.
 */
class SimpleCal_Model_CalendarMonthTest extends PHPUnit_Framework_TestCase
{
    private $_tz = null;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        $this->_tz = date_default_timezone_get();
        date_default_timezone_set('GMT');
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        date_default_timezone_set($this->_tz);
    }

    /**
     * Tests SimpleCal_Model_Calendar_Month constructor and the getStartTime method
     * 
     * @dataProvider constructorStartEndTimeProvider
     */
    public function testConstructGetStartTime($month, $year, $expStartTime, $expEndTime)
    {
        $cal = new SimpleCal_Model_Calendar_Month($month, $year);
        $this->assertEquals($expStartTime, $cal->getStartTime());
    }
    
    /**
     * Tests SimpleCal_Model_Calendar_Month constructor and the getEndTime method
     * 
     * @dataProvider constructorStartEndTimeProvider
     */
    public function testConstructorGetEndTime($month, $year, $expStartTime, $expEndTime)
    {
        $cal = new SimpleCal_Model_Calendar_Month($month, $year);
        $this->assertEquals($expEndTime, $cal->getEndTime());
    }

    /**
     * @dataProvider monthWeekStartTimeProvider
     */
    public function testGetFirstWeekStartTime($month, $year, $startDay, $expWeekStartTime)
    {
        $cal = new SimpleCal_Model_Calendar_Month($month, $year);
        $this->assertEquals($expWeekStartTime, $cal->getFirstWeekStartTime($startDay));
    }

    /**
     * @dataProvider monthWeekEndTimeProvider
     */
    public function testGetLastWeekEndTime($month, $year, $startDay, $expWeekEndTime)
    {
        $cal = new SimpleCal_Model_Calendar_Month($month, $year);
        $this->assertEquals($expWeekEndTime, $cal->getLastWeekEndTime($startDay));
    }

    /**
     * Data Providers
     */
    
    /**
     * Data provider for simple constructor + getStartTime tests
     * 
     * @return array
     */
    static public function constructorStartEndTimeProvider()
    {
        $tz = date_default_timezone_get();
        date_default_timezone_set('GMT');
        
        return array(
            array(11, 2009, 1257033600, 1259625599),
            array(2, 1979, 286675200, 289094399),
            array(1, null, mktime(0, 0, 0, 1, 1), mktime(23, 59, 59, 1, 31)),
            array(null, null, mktime(0, 0, 0, date("n"), 1), 
                mktime(0, 0, 0, date("n") + 1, 1) - 1),
            array(12, 2008, 1228089600, 1230767999)
        );
        
        date_default_timezone_set($tz);
    }
    
    /**
     * Data provider for month first week start time tests
     * 
     * @return array
     */
    static public function monthWeekStartTimeProvider()
    {
        return array(
            array(11, 2009, 0, 1257033600), // Nov 1  2009
            array(11, 2009, 2, 1256601600), // Oct 27 2009 
            array(12, 2009, 0, 1259452800), // Nov 29 2009 
            array(12, 2009, 1, 1259539200)  // Nov 30 2009 
        );
    }
    
    static public function monthWeekEndTimeProvider()
    {
        return array(
            array(11, 2009, 0, 1259971200 + 86399), // Dec 5  2009
            array(11, 2009, 2, 1259539200 + 86399), // Nov 30 2009
            array(12, 2009, 0, 1262390400 + 86399), // Jan 2  2010 
            array(12, 2009, 1, 1262476800 + 86399)  // Jan 3  2010
        );
    }
}