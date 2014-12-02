<?php
class Site_Form_Element_CommonName extends Zend_Form_Element_Text{

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name=NAME,$maxlength=35,$required=true){
       parent::__construct($name, array(
            'required'    => $required,
            'label'       => $name,
            'size'        => '40',
            'maxlength'   => $maxlength,
            'validators'  => array(
                array('StringLength', false, array( 3, 35)),
                array('Regex', false, array(REG_NAME))
             ),
            'filters'=> array('Cleare','StringTrim')
        ));
       // $this->addDecorator('HtmlTag', array('tag' => 'p'))
       //      ->addDecorator('Label', null);
    }
}