<?php
class Site_Form_Element_CommonHash extends Zend_Form_Element_Hash{

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name='code'){
        parent::__construct($name, array(
            'salt' => 'unique',
        ));
        $this->removeDecorator('HtmlTag')
             ->removeDecorator('Label');
    }
}