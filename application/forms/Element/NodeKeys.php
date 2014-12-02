<?php
class Site_Form_Element_NodeKeys extends Zend_Form_Element_Textarea{

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name=KEY){
       parent::__construct($name, array(
            'label'       => 'keywords',
            'rows'        => '2',
            'cols'        => '62',
            'validators'  => array(
                array('StringLength', true, array(0, 500)),
                array('Regex', false, array(REG_WORDS))
             ),
             'filters'=> array('Cleare','StringTrim')
        ));
    }
}
