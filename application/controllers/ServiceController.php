<?php
/**
 * ServiceController
 */
class ServiceController extends Zend_Controller_Action
{
    /**
     * The default action - show the home page
     */
    public function emailAction ()
    {
        $params = ZendJobQueue::getCurrentJobParams();
        // TODO: throw exception when not called from a job, in the init method?

        if (!$this->_helper->requestValidation($params['password'])) {
            ZendJobQueue::setCurrentJobStatus(ZendJobQueue::FAILED);
            // TODO: not very nice, should be logged
            exit('Terminated, validation error!');
        }

        $mail = new Zend_Mail();
        $mail->setBodyText($params['body']);
        $mail->setFrom('somebody@example.com', 'SimpleCal Admin');
        $mail->addTo($params['to']);
        $mail->setSubject($params['subject']);
        #$mail->send();

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
    }
}

