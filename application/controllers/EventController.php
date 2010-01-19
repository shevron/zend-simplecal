<?php

class EventController extends Zend_Controller_Action
{
    // TODO: remove the constant to config
    const JOB_APP_NAME = 'SimpleCal';
    const JOB_INVITATION_PREFIX = 'Invitation for event ';
    const JOB_REMINDER_PREFIX = 'Reminder for event ';
    
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function editAction()
    {
        $form = new SimpleCal_Form_Event(array(
            'action' => $this->view->baseUrl . '/event/edit'
        ));

        $form->populate($this->_getAllParams()); 

        if ($this->getRequest()->isPost()) {
            $params = $this->_getAllParams();
            if ($form->isValid($params)) {

                $event = SimpleCal_Model_Event::getInstanceByForm($form);
                $event->save();

                if ($month = $event->getStartTimeMonth()) {
                    $this->_redirect("/month/$month", array('exit' => false));
                } else {
                    $this->_redirect('/', array('exit' => false));
                }
                
                $this->_setParam('event_id', $event->getId());
                $this->_forward('invitation');
                return;
            }
        }
        elseif ($eventId = $this->_getParam('id')) {
            $event = SimpleCal_Model_Event::getInstanceById($eventId);
            $form->populate($event->getFormContainer());
        }

        $this->view->form = $form;
    }
    
    public function deleteAction() {
        $eventId = $this->_getParam('id');  
        $event = SimpleCal_Model_Event::getInstanceById($eventId); 
        $month = date("Y/m", $event->getStartTime());
        if ($month) {
            $this->_redirect("/month/$month", array('exit' => false));
        } else {
            $this->_redirect('/', array('exit' => false));
        } 
        $event->delete();
        
        $this->_setParam('event_id', $eventId);
        $this->_forward('removereminder');
    }
    
    public function invitationAction() {
        $eventId = $this->_getParam('event_id');
        $event = SimpleCal_Model_Event::getInstanceById($eventId);
        
        foreach ($event->getInvitationEmails() as $email) {
            if ($email == '') continue;
            
            try {
                // instantiate JobQueue if not already happened
                if (!isset($queue)) {
                    $queue = new ZendJobQueue();
                    $jobUrl = $this->view->baseUrl('/service/email');
                }
                
                $jobOptions = array(
                    'name' => self::JOB_INVITATION_PREFIX . $eventId,
                	'app_id' => self::JOB_APP_NAME // Obviously 'app_id' doesn't work
                );
                
                $invitationMod = new SimpleCal_Model_Email_Invitation($event->getId(), $email);
                $invitationMod->assignToView('event', $event);
                
                $jobParams = $this->_helper->jqEmail($invitationMod);
                 
                $queue->createHttpJob(
        			$jobUrl,
                    $jobParams,
                    $jobOptions
                );
            }
            catch (SimpleCal_Exception $e) {
                //TODO: tell the User that he entered an invaild email address
            }
        }    
        
        $this->_helper->actionStack('reminder');
        $this->_helper->actionStack('removereminder');
    }
    
    public function reminderAction() {
        $eventId = $this->_getParam('event_id');
        $event = SimpleCal_Model_Event::getInstanceById($eventId);
        
        foreach ($event->getInvitationEmails() as $email) {
            if ($email == '') continue;
            
            try {
                $reminderMod = new SimpleCal_Model_Email_Reminder($email);
                $reminderMod->assignToView('event', $event);
                
                $jobParams = $this->_helper->jqEmail($reminderMod);
                $queue = new ZendJobQueue();
                
                $jobOptions = array(
                    'name' => self::JOB_REMINDER_PREFIX . $eventId,
                    'schedule_time' => $event->getReminderTime(),
                	'app_id' => self::JOB_APP_NAME // Obviously 'app_id' doesn't work
                );
                
                $queue->createHttpJob(
                    '/service/email',
                    $jobParams,
                    $jobOptions
                );
            }
            catch (SimpleCal_Exception $e) {
                //TODO: tell the User that he entered an invaild email address
            }
        }
    }
    
    public function removereminderAction() {
        $eventId = $this->_getParam('event_id');
        $queue = new ZendJobQueue();
        
        $jobQuery = array(
            'name' => self::JOB_REMINDER_PREFIX . $eventId
        );

        $jobList = $queue->getJobsList($jobQuery);
        
        foreach ($jobList as $job) {
            $queue->removeJob($job['id']);    
        }
    }
    
    public function reinventAction() {
        $eventId = $this->_getParam('id');
        $queue = new ZendJobQueue();
        
        $jobQuery = array(
            'name' => self::JOB_INVITATION_PREFIX . $eventId
        );
        
        $jobList = $queue->getJobsList($jobQuery);
        
        foreach ($jobList as $job) {
            $queue->restartJob($job['id']);    
        }
        
        $this->_redirect('/');
    }
}
