<?php


class SimpleCal_Form_ChangeTimezone extends Zend_Form
{
    public function init()
    {
        Zend_Dojo::enableForm($this);

        $this->setOptions(array(
            'method' => 'post',
        ));

        $timezone = new Zend_Dojo_Form_Element_FilteringSelect(array(
            'name' => 'timezone',
            'label' => 'Timezone',
            'autocomplete' => false
        ));
        $timezone->addMultiOptions(
            array_combine(
                DateTimeZone::listIdentifiers(),
                DateTimeZone::listIdentifiers()));

        $submit = new Zend_Form_Element_Submit(array(
            'name'  => 'set',
            'label' => 'Set Timezone'
        ));

        $this->addElements(array($timezone, $submit));

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