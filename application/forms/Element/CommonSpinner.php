<?php
class Site_Form_Element_CommonSpinner extends ZendX_JQuery_Form_Element_Spinner {

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name, $label=null) {
        if(empty($label))  $label=$name;
        parent::__construct($name, array(
            'label' => $label,
            'size' => '10',
            'required' => false,
            'maxlength' => '10',
            'validators' => array(
                array('Int', true)
            )
        ));
        $this->setJQueryParams(array(
            'min' => -9999,
            'max' => 99999999,
            'allowNull' => true,
            'showOn' => 'both'
        ));
    }
}
