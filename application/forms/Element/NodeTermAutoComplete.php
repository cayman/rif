<?php
class Site_Form_Element_NodeTermAutoComplete extends ZendX_JQuery_Form_Element_AutoComplete {

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name, $label, $terms,$required=false) {

        $termsLookup = array();
        foreach ($terms as $term) {
            $termsLookup[]= $term->name;
        }

        parent::__construct($name,
            array(
                'label' => $label,
                'required' => $required,
                'size' => '77',
                'maxlength' => '256',
                'validators' => array(
                    array('StringLength', true, array(0, 256)),
                    array('Regex', false, array(REG_WORDS))
                ),
                'filters' => array('Cleare', 'StringTrim'/*, 'StringToLower'*/)
            )
        );


        $this->setJQueryParams(array(
            'minChars' => 1,
            'delimiter' => ", ", ///(,|, )\s*/",
            'maxHeight' => 400,
            'width' =>300,
            'deferRequestBy'=> 0,
            'zIndex' => 9999,
            'lookup' => $termsLookup,
            'data' => 'not use',
        ));

        if($required)
            $this->addValidator('NotEmpty',true);        
    }
}
