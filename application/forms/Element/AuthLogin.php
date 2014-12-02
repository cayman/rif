<?php
class Site_Form_Element_AuthLogin extends Zend_Form_Element_Text{

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name='login'){
       parent::__construct($name, array(
            'required'    => true,
            'label'       => $name,
            'size'        => '20',
            'maxlength'   => '20',
            'validators'  => array(
                array('Alnum', true, array(false)),
                array('StringLength', true, array(6, 20)),
             ),
            'filters'     => array('StringTrim'),
        ));
    }
}

