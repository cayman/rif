<?php
class Site_Form_Element_CommonHidden extends Zend_Form_Element_Hidden{

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name='field'){
       parent::__construct($name);
        $this->removeDecorator('HtmlTag')
             ->removeDecorator('Label');
    }
}
