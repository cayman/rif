<?php
class Site_Form_Element_NodeTextarea extends Zend_Form_Element_Textarea {

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name = TEXT,$rows) {
        parent::__construct($name, array(
            'required' => true,
            'id' =>'bbcode',
            'label' => $name,
            'rows' => $rows,
            'cols' => '61',
            'validators' => array(
                array('StringLength', true, array(10, 2000)),
                array('Regex', false, array(REG_TEXT))
            ),
            'filters' => array('Cleare', 'StringTrim')
        ));

        $this->clearDecorators()
        ->addDecorator('ViewHelper')
        //->addDecorator('BBEditor')
        ->addDecorator('Markitup')
        ->addDecorator('Description', array('tag' => 'p', 'class' => 'description'))
        ->addDecorator('HtmlTag', array('tag' => 'dd', 'id' => $this->getName() . '-element'))
        ->addDecorator('Label', array('tag' => 'dt'))
        ->addDecorator('Errors');
    }
}
