<?php
class Site_Form_Element_CommonDesc extends Zend_Form_Element_Text{

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name=DESC,$max=256){
       parent::__construct($name, array(
            'label'       => $name,
            'size'        => '83',
            'maxlength'   => $max,
            'validators'  => array(
                array('StringLength', true, array(0,$max)),
                array('Regex', false, array(REG_TEXT))
             ),
            'filters'=> array('Cleare','StringTrim'),
        ));
    }
}
