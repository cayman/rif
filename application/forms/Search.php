<?php
class Site_Form_Search extends Zend_Form
{

    public function __construct($action)
    {
        Log::info($this,'actionUrl',$action);
        parent::__construct();
        $this->addElementPrefixPath('Site_Form_Filter',APPLICATION_PATH.'/forms/Filter','filter');
        $this->addElementPrefixPath('Site_Form_Decorator', APPLICATION_PATH.'/forms/Decorator', 'decorator');
        $this->setAction($action);
    }

    public function init()
    {
        parent::init();
        $this->setMethod('post')
             ->setAttrib('class', 'find_form')
             ->setAttrib('id','find_form');
        $page = new Site_Form_Element_CommonHidden(PAGE);
        $key = new Zend_Form_Element_Text(KEY, array(
            'required'    => true,
            'size'        => 25,
            'maxlength'   => 25,
            'validators'  => array(
                array('StringLength', false, array( 4, 35)),
                array('regex', true, array('/^[\w+]/i'))
             ),
            'filters'=> array('Cleare','StringTrim'),
            'disableLoadDefaultDecorators' => true
        ));
        $key->addDecorator('ViewHelper')
                ->addDecorator('Errors');

        $submit = new Zend_Form_Element_Submit('save', array(
            'label' => SEARCH,'disableLoadDefaultDecorators' => true));
        $submit->addDecorator('ViewHelper')
                ->addDecorator('Errors');

        $this->addElement($page)
             ->addElement($key)
             ->addElement($submit);


    }

}
