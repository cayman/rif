<?php
class Site_Form_Element_CommonCheckbox extends Zend_Form_Element_Checkbox{

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name){
       parent::__construct($name, array(
            'label'       => $name
        ));

    }
}