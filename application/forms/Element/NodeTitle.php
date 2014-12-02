<?php
class Site_Form_Element_NodeTitle extends Zend_Form_Element_Text {

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name = TITLE) {
        parent::__construct($name, array(
            'label' => $name,
            'size' => '83',
            'maxlength' => '256',
            'validators' => array(
                array('StringLength', true, array(0, 256)),
                array('Regex', false, array(REG_TEXT))
            ),
            'filters' => array('Cleare', 'StringTrim'),
        ));
    }
}