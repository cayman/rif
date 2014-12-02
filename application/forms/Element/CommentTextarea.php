<?php
class Site_Form_Element_CommentTextarea extends Zend_Form_Element_Textarea{

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name = TEXT){

        parent::__construct($name, array(
            'required'    => true,
            //'id' =>'bbcode',
            'label'       => COMMENT,
            'rows'        => '10',
            'cols'        => '60',
            'validators'  => array(
                array('StringLength', true, array(0, 5000)),
                array('Regex', false, array(REG_TEXT))
             ),
            'filters'=> array('Cleare','StringTrim')
        ));
        /*$this->addDecorator('HtmlTag', array('tag' => 'p'))
             ->addDecorator('Label', null);*/

        /*$this->clearDecorators()
        ->addDecorator('ViewHelper')
        ->addDecorator('Markitup')
        ->addDecorator('Description', array('tag' => 'p', 'class' => 'description'))
        ->addDecorator('HtmlTag', array('tag' => 'dd', 'id' => $this->getName() . '-element'))
        ->addDecorator('Label', array('tag' => 'dt'))
        ->addDecorator('Errors');*/
    }
}