<?php
class Site_Form_Type extends Zend_Form {

    public function __construct($action) {
        Log::info($this, 'actionUrl', $action);
        parent::__construct();
        $this->addElementPrefixPath('Site_Form_Filter', APPLICATION_PATH . '/forms/Filter', 'filter');
        $this->addElementPrefixPath('Site_Form_Decorator', APPLICATION_PATH . '/forms/Decorator', 'decorator');
        $this->setAction($action);
    }

    public function init() {
        $this->setMethod('post')
        ->setAttrib('class', 'edit_form')
        ->setAttrib(ID, 'type_form');


        $name = new Site_Form_Element_CommonName(NAME);
        $code = new Site_Form_Element_CommonName(CODE);
        $desc = new Site_Form_Element_CommonDesc(DESC);
        $rows = new Site_Form_Element_CommonSpinner('rows');
        $comment = new Site_Form_Element_CommonCheckbox(COMMENT);

        $page = new Site_Form_Element_CommonHidden(PAGE);

        $submit = new Site_Form_Element_Submit();
        $this->addElement($name)
        ->addElement($code)
        ->addElement($desc)
        ->addElement($rows)
        ->addElement($comment)
        ->addElement($submit)
        ->addElement($page);


    }

    public function copy(Site_Model_Type $type) {
        $id = $this->getValue(ID);
        if (isset($id) && $id > 0) $type->id = $id;
        $type->name = $this->getValue(NAME);
        $type->code = $this->getValue(CODE);
        $type->desc = $this->getValue(DESC);
        $type->text_rows = $this->getValue('rows');
        $type->comment = $this->getValue(COMMENT);

    }




    public function populate(Site_Model_Classifier $user) {
        $this->setDefaults(array(
            ID => $user->id,
            NAME => $user->name,
            CODE => $user->code,
            DESC => $user->desc,
            'rows' => $user->order,
            COMMENT => $user->autocomplete,
        ));
    }
}
