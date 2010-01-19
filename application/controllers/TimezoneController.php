<?php
/**
 * TimezoneController
 *
 */
require_once 'Zend/Controller/Action.php';
class TimezoneController extends Zend_Controller_Action
{
    /**
     *
     */
    public function indexAction ()
    {
        $tz_session = new Zend_Session_Namespace(
        	'SimpleCal_Controller_Plugin_Timezone');

        $form = new SimpleCal_Form_ChangeTimezone(array(
            'action' => $this->view->baseUrl . '/timezone'));

        $form->populate($this->_getAllParams());

        if ($this->getRequest()->isPost() === false) {
           if($tz_session->timezone !== null)
           {
               $form->setDefault('timezone', $tz_session->timezone);
           }
        }
        else
        {
            $params = $this->_getAllParams();
            if ($form->isValid($params)) {
               $tz_session->timezone = $params['timezone'];
               date_default_timezone_set($params['timezone']);
               $this->_redirect('/');
               exit;
            }
        }

        $this->view->form = $form;
    }
}

