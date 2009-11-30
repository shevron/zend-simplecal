<?php


class SimpleCal_Form_CreateEvent extends Zend_Form
{
    public function init()
    {
        Zend_Dojo::enableForm($this);
        
        $this->setOptions(array(
            'method' => 'post',
        ));
        
        $title = new Zend_Dojo_Form_Element_TextBox(array(
            'name'       => 'title',
            'label'      => 'Event Title',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'length' => array(
                    'validator' => 'StringLength',
                    'options' => array('min' => 4, 'max' => 100)
                )
            )
        ));
        
        $date = new Zend_Dojo_Form_Element_DateTextBox(array(
            'name'       => 'date',
            'label'      => 'Date',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'date' => array(
                    'validator' => 'Date'
                )
            )
        ));
        
        $date->setFormatLength('long');
        
        $time = new Zend_Dojo_Form_Element_TimeTextBox(array(
            'name'       => 'time',
            'label'      => 'Time',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'time' => array(
                    'validator' => 'RegEx',
                    'options'   => array('pattern' => '/^T(?:[0-1][0-9]|2[0-3]):[0-5][0-9]:00$/')
                )
            )
        ));
        
        $desc = new Zend_Dojo_Form_Element_SimpleTextarea(array(
            'name'    => 'description',
            'label'   => 'Description',
            'filters' => array('StringTrim'),
            'cols'    => 80,
            'rows'    => 5
        ));
        
        $submit = new Zend_Form_Element_Submit(array(
            'name'  => 'create',
            'label' => 'Create Event'
        ));
        
        $this->addElements(array($title, $date, $time, $desc, $submit));
        
        /**
         * Set decorators
         */
        
        $this->clearDecorators();
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'form-container')),
            'Form'
        ));
    }
}