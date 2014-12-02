<?php
class Site_Form_Element_Submit extends Zend_Form_Element_Submit{

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name='save'){
        parent::__construct($name, array(
                    'label' => 'save',
                ));
        /*$this->setDecorators(array('ViewHelper',
                    array('HtmlTag', array('tag' => 'p'))
                    ));*/
		$this->removeDecorator('Label');
    }
}