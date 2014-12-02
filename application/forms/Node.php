<?php
class Site_Form_Node extends Zend_Form {
    private $_key_filter;
    private $_desc_filter;
    private $_title_filter;
    private $type;


    public function __construct(Site_Model_Type $type, $action) {
        $this->type = $type;
        parent::__construct();
        $this->addElementPrefixPath('Site_Form_Filter', APPLICATION_PATH . '/forms/Filter', 'filter');
        $this->addElementPrefixPath('Site_Form_Decorator', APPLICATION_PATH . '/forms/Decorator', 'decorator');
        $this->_key_filter = new Site_Form_Filter_Keywords();
        $this->_desc_filter = new Site_Form_Filter_Description();
        $this->_title_filter = new Site_Form_Filter_Title();
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
        $type_id = new Site_Form_Element_CommonHidden(TYPE);
        $type_id->setValue($this->type->id);

        $title = new Site_Form_Element_NodeTitle(TITLE);
        $text = new Site_Form_Element_NodeTextarea(TEXT, $this->type->text_rows);
        $date = new Site_Form_Element_CommonDate(DATE);
        $desc = new Site_Form_Element_CommonDesc(DESC);
        $key = new Site_Form_Element_NodeKeys(KEY);
        $submit = new Site_Form_Element_Submit();
        $page = new Site_Form_Element_CommonHidden(PAGE);


        $this->addElement($id)
                ->addElement($type_id)
                ->addElement($date)
                ->addElement($title)
                ->addElement($desc)
                ->addElement($text)
                ->addElement($key)
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
        $date = new Zend_Date($node->date);
        $this->setDefaults(array(
            ID => $node->id,
            TITLE => $node->title,
            TEXT => $node->text,
            DESC => $node->desc,
            KEY => $node->key,
            DATE => $date->toString(),
        ));
        if (!empty($node->terms))
            $this->setTerms($node->terms);
    }

    public function setDate($date) {
        $zdate = new Zend_Date($date);
        $this->setDefault(DATE, $zdate->toString());
    }
    public function setPage($uri) {
        $this->setDefault(PAGE, $uri);
    }

    public function setText($text) {
        $this->setDefault(TEXT, $text);
    }

    public function setTerms(array  $terms) {
        foreach ($terms as $classifier_code => $class_terms) {
            $subForm = $this->getSubForm($classifier_code);
            if (isset($subForm)) $subForm->populate($class_terms);
        }
    }

    public function copy(Site_Model_Node $node) {
        $id = $this->getValue(ID);
        $date = new Zend_Date($this->getValue(DATE));
        $title = $this->getValue(TITLE);
        $text = $this->getValue(TEXT);
        $key = $this->getValue(KEY);
        if (mb_strlen($key) == 0)
            $key = $this->_key_filter->filter($text);
        $desc = $this->getValue(DESC);
        if (mb_strlen($desc) == 0) {
            $desc = $this->_desc_filter->filter($text);
        }
        if (mb_strlen($title) == 0) {
            $title = $this->_title_filter->filter($text);
        }

        if (isset($id) && $id > 0) $node->id = $id;
        $node->type_id = $this->getValue(TYPE);
        $node->date = $date->toString('YYYY-MM-dd HH:mm:ss');
        $node->title = $title;
        $node->text = $text;
        $node->key = $key;
        $node->desc = $desc;
        $node->terms = $this->getTerms();
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
