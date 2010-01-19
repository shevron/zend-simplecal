<?php

class SimpleCal_Controller_Plugin_Timezone extends Zend_Controller_Plugin_Abstract
{
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $namespace = new Zend_Session_Namespace(
        	'SimpleCal_Controller_Plugin_Timezone');

        if($namespace->timezone !== null)
        {
            date_default_timezone_set($namespace->timezone);
        }
    }
}