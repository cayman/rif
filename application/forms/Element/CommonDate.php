<?php
class Site_Form_Element_CommonDate extends Zend_Form_Element_Text{

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name=DATE){
       parent::__construct($name, array(
            'label'       => DATE,
            'size'        => '19',
            'required'    => true,
            'maxlength'   => '19',
            'validators'  => array(
                 array('Date', true, array('dd.MM.yyyy HH.mm.ss'))
                 ),
        ));
        $this->addDecorator('Calendar');
    }
}
