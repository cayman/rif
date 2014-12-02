<?php
class Site_Form_Element_NodeTermValue extends Zend_Form_Element_Text {

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name, $label) {
        parent::__construct($name, array(
            'label' => $label,
             'size' => '40',
             'maxlength' => '256',
            'required' => false,
            'validators' => array(
                array('StringLength', false, array(0, 256)),
                array('Regex', false, array(REG_TEXT))
            ),
            'filters' => array('Cleare', 'StringTrim')
        ));
    }
}        