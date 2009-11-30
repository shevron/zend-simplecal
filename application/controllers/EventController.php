<?php

class EventController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }
    
    public function createAction()
    {
        $form = new SimpleCal_Form_CreateEvent(array(
            'action' => $this->view->baseUrl . '/event/create'
        ));
        
        $form->populate($this->_getAllParams());
        
        if ($this->getRequest()->isPost()) {
            $params = $this->_getAllParams();
            if ($form->isValid($params)) {
                $event = new SimpleCal_Model_Event(array(
                    'start_time' => strtotime($form->getValue('date')),
                    
                ));
                
                // SAVE IT!
                
                $month = date("Y/m", $event->getStartTime());
                if ($month) {
                    $this->_redirect("/month/$month");
                } else {
                    $this->_redirect('/');
                }
            }
        }
        
        $this->view->form = $form;
    }
}