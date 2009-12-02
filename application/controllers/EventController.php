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
                
                $startTime = strtotime($form->getValue('date') . " " . $form->getValue('time'));
                
                $event = new SimpleCal_Model_Event(array(
                    'start_time'  => $startTime,
                    'end_time'    => $startTime + 1800, // TODO: Implement this!
                    'title'       => $form->getValue('title'),
                    'description' => $form->getValue('description')
                ));
                
                $event->save();
                
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