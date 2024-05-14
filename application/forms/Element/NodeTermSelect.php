<?php
class Site_Form_Element_NodeTermSelect extends Zend_Form_Element_Select {

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name, $label, $terms, $required = false) {

        $termsOptions = array();
        $termsOptions[0] = "- не задано -";
        if($required==false) $termsValidate[] = 0;
        foreach ($terms as $term){
            $termsOptions[$term->id] = $term->name;
        }
        parent::__construct($name,
            array(
                'label' => $label,
                'required' => $required,
                'multiOptions' => $termsOptions
            ));
        $this->addFilter('Int');
        if($required) $this->addValidator('NotEmpty', true,array('integer','zero'));
        $this->addValidator('InArray', false, array(array_keys($termsOptions)));
    }
}
