<?php
class Site_Form_NodeTerms extends Zend_Form {

    private $type;

    public function __construct(Site_Model_Type $type,$action) {
        $this->type=$type;
        parent::__construct();
        $this->addElementPrefixPath('Site_Form_Filter', APPLICATION_PATH . '/forms/Filter', 'filter');
        $this->addElementPrefixPath('Site_Form_Decorator', APPLICATION_PATH . '/forms/Decorator', 'decorator');
        Log::info($this, 'actionUrl', $action);
        $this->setAction($action);
    }


    public function init() {
        parent::init();
        ZendX_JQuery::enableForm($this);
        $this->setMethod('post')
            ->setAttrib('class', 'edit_form')
            ->setAttrib(ID, 'node_form');

        $id = new Site_Form_Element_CommonHidden(ID);
        $page = new Site_Form_Element_CommonHidden(PAGE);
        $submit = new Site_Form_Element_Submit();

        $this->addElement($id)
              ->addElement($page);


        foreach ($this->type->classifier as $classifier) {
            $term_form = ($classifier->autocomplete) ?
                    new Site_Form_NodeTermAutocomplete($classifier) :
                    new Site_Form_NodeTermSelect($classifier);
            $this->addSubForm($term_form, $classifier->code);
        }

        $this->addElement($submit);
    }

    public function populate(Site_Model_Node $node) {
        $this->setDefaults(array(
            ID => $node->id,
        ));
        if (!empty($node->terms))
            $this->setTerms($node->terms);
    }

    public function setTerms(array  $terms) {
        foreach ($terms as $classifier_code => $class_terms) {
            $subForm = $this->getSubForm($classifier_code);
            if (isset($subForm)) $subForm->populate($class_terms);
        }
    }

    public function getTerms() {
        $terms = array();
        foreach ($this->getSubForms() as $classifier_code => $sub_form) {
            $terms[$classifier_code]=$sub_form->getTerms();
            Log::info($this, "getTerms", $terms);
        }
        return $terms;
    }

    public function getPage() {
       return  $this->getValue(PAGE);
    }


}
