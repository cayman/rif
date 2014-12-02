<?php
class Site_Form_Element_CommentLevel extends Zend_Form_Element_Select{

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($values,$name){
       parent::__construct($name, array(
            'required'    => true,
            'label'       => HIDE,
            'validators'  => array(
                array('InArray', true, array(array_keys($values)))
             ),
            'multiOptions'=>$values
        ));
       // $this->addDecorator('HtmlTag', array('tag' => 'p'))
       //      ->addDecorator('Label', null);
    }
}