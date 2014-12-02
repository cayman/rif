<?php
class Site_Form_Element_TermTextarea extends Zend_Form_Element_Textarea{

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name=DESC,$max=256){

        parent::__construct($name, array(
            'required'    => false,
            'label'       => $name,
            'rows'        => '4',
            'cols'        => '60',
            'validators'  => array(
                array('StringLength', true, array(0, $max)),
                array('Regex', false, array(REG_TEXT))
             ),
        ));
    }
}