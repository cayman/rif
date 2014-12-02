<?php
class Site_Form_CommentEdit extends Zend_Form {
    public function __construct($action) {
        Log::info($this, 'actionUrl', $action);
        $this->addElementPrefixPath('Site_Form_Filter', APPLICATION_PATH . '/forms/Filter', 'filter');
        $this->addElementPrefixPath('Site_Form_Decorator', APPLICATION_PATH . '/forms/Decorator', 'decorator');
        parent::__construct();
        $this->setAction($action);
    }

    public function init() {
        parent::init();
        ZendX_JQuery::enableForm($this);
        $this->setMethod('post')
        ->setAttrib('class', 'edit_form')
        ->setAttrib('id', 'comment_form');

        $name = new Site_Form_Element_CommonName(NAME);
        $comment = new Site_Form_Element_CommentTextarea(TEXT);


        $hide = new Site_Form_Element_CommonSelect(LEVEL,  Site_Service_Registry::get(COMMENT)->getLevelsOptions());
        $submit = new Site_Form_Element_Submit();
        $page = new Site_Form_Element_CommonHidden(PAGE);
        
        $this->addElement($name)
        ->addElement($comment)
        ->addElement($hide)
        ->addElement($submit)
        ->addElement($page);
    }


    public function populate(Site_Model_Comment  $model) {
        $this->setDefaults(array(
            NAME => $model->name,
            TEXT => $model->text,
            LEVEL => $model->level
        ));
    }

    public function setPage($uri) {
        $this->setDefault(PAGE, $uri);
    }

    public function copy(Site_Model_Comment $model) {
        $model->name = $this->getValue(NAME);
        $model->text = $this->getValue(TEXT);
        $model->level = $this->getValue(LEVEL);
    }

}