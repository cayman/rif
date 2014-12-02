<?php
class Site_Form_Term extends Zend_Form
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
             ->setAttrib('class', 'edit_form')
             ->setAttrib('id','term_form');
        $taxonomy = Site_Service_Registry::get(TAXONOMY);

        $id = new Site_Form_Element_CommonHidden(ID);
        $classifierSelect = new Site_Form_Element_CommonSelect(CLASSIFIER,$taxonomy->getClassifiersOptions());
        $date = new Site_Form_Element_CommonDate(DATE);
        $name = new Site_Form_Element_CommonName(NAME,24);
        $desc = new Site_Form_Element_TermTextarea(DESC);
        $order = new Site_Form_Element_CommonSpinner(ORDER);
        $page = new Site_Form_Element_CommonHidden(PAGE);
        
        $submit = new Site_Form_Element_Submit();
        $this->addElement($id)
             ->addElement($classifierSelect)
             ->addElement($date)
             ->addElement($name)
             ->addElement($desc)
             ->addElement($order)
             ->addElement($submit)
             ->addElement($page);


    }

    public function copy($term){
        $id=$this->getValue(ID);
        $date = new Zend_Date($this->getValue(DATE));

        if (isset($id) && $id > 0) $term->id = $id;
        $term->class_id = $this->getValue(CLASSIFIER);
        $term->name = $this->getValue(NAME);
        $term->desc = $this->getValue(DESC);
        $term->order = $this->getValue(ORDER);
        $term->date = $date->toString('YYYY-MM-dd HH:mm:ss');;
    }

   

    public function populate(Site_Model_Term $term) {
        $date = new Zend_Date($term->date);
        $this->setDefaults(array(
            ID => $term->id,
            CLASSIFIER => $term->class_id,
            NAME => $term->name,
            DESC => $term->desc,
            ORDER => $term->order,
            DATE => $date->toString(),
        ));
    }

    public function setDate($date) {
        $zdate = new Zend_Date($date);
        $this->setDefault(DATE, $zdate->toString());
    }

    public function setName($name) {
        $this->setDefault(NAME, $name);
    }
    public function setDesc($desc) {
        $this->setDefault(DESC, $desc);
    }

    public function setClass($class_id) {
        $this->setDefault(CLASSIFIER, $class_id);
    }

    public function setPage($uri) {
        $this->setDefault(PAGE, $uri);
    }




}
