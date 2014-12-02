<?php
class Site_Form_Element_AuthPassword extends Zend_Form_Element_Password{

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name='password'){
        parent::__construct($name, array(
            'required'    => true,
            'label'       => $name,
            'size'   => '20',
            'maxlength'   => '20',
            'validators'  => array(
                array('Alnum', true, array(false)),
                array('StringLength', true, array(0, 20))
             ),
            'filters'     => array('StringTrim'),
        ));
    }
}