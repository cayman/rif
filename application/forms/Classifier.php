<?php
class Site_Form_Classifier extends Zend_Form {

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
        ->setAttrib(TITLE, CLASSIFIER)
        ->setAttrib(ID, 'class_form');

        $name = new Site_Form_Element_CommonName(NAME);
        $code = new Site_Form_Element_CommonName(CODE);
        $desc = new Site_Form_Element_CommonDesc(DESC);
        $order = new Site_Form_Element_CommonSpinner(ORDER);
        $autocomplete = new Site_Form_Element_CommonCheckbox(AUTOCOMPLETE);
        $multiple = new Site_Form_Element_CommonCheckbox(MULTIPLE);

        $value_type = new Site_Form_Element_CommonSelect('value_type', Site_Service_Registry::get(TAXONOMY)->getTermValuesOptions());
        $value_name = new Site_Form_Element_CommonName('value_name',20,false);

        $type = new Site_Form_Element_CommonMultiCheckbox(TYPE, Site_Service_Registry::get(TYPE)->getTypesOptions());
        $page = new Site_Form_Element_CommonHidden(PAGE);        

        $submit = new Site_Form_Element_Submit();
        $this->addElement($name)
        ->addElement($code)
        ->addElement($desc)
        ->addElement($order)
        ->addElement($autocomplete)
        ->addElement($multiple)
        ->addElement($value_type)
        ->addElement($value_name)
        ->addElement($type)
        ->addElement($submit)
        ->addElement($page);


    }

    public function copy(Site_Model_Classifier $classifier) {
        $id = $this->getValue(ID);
        if (isset($id) && $id > 0) $classifier->id = $id;
        $classifier->name = $this->getValue(NAME);
        $classifier->code = $this->getValue(CODE);
        $classifier->desc = $this->getValue(DESC);
        $classifier->order = $this->getValue(ORDER);
        $classifier->autocomplete = $this->getValue(AUTOCOMPLETE);
        $classifier->multiple = $this->getValue(MULTIPLE);
        $classifier->value_type = $this->getValue('value_type');
        $classifier->value_name = $this->getValue('value_name');
       // $classifier->setTypes($this->getValue(TYPE)); //todo
    }


    public function populate(Site_Model_Classifier $classifier) {
        $type_array = array();
        foreach ($classifier->getTypesClassifier() as $type) {
            $type_array[] = $type->type_id;
        }

        $this->setDefaults(array(
            ID => $classifier->id,
            NAME => $classifier->name,
            CODE => $classifier->code,
            DESC => $classifier->desc,
            ORDER => $classifier->order,
            AUTOCOMPLETE => $classifier->autocomplete,
            MULTIPLE => $classifier->multiple,
            'value_type' => $classifier->value_type,
            'value_name' => $classifier->value_name,
           // TYPE => $type_array,
        ));
    }
}
