<?php
class Site_Form_CommentAdd extends Zend_Form
{
    public function __construct($action)
    {
        Log::info($this,'actionUrl',$action);
        parent::__construct();
        $this->addElementPrefixPath('Site_Form_Filter',APPLICATION_PATH.'/forms/Filter','filter');
        $this->addElementPrefixPath('Site_Form_Decorator', APPLICATION_PATH . '/forms/Decorator', 'decorator');
        $this->setAction($action);
    }

    public function init()
    {
        parent::init();
        ZendX_JQuery::enableForm($this);
        $this->setMethod('post')
             ->setAttrib('class', 'edit_form')
             ->setAttrib('id','comment_form');
        
        $name = new Site_Form_Element_CommonName(NAME);
        $comment = new Site_Form_Element_CommentTextarea(TEXT);
        $captcha = new Site_Form_Element_CommentCaptcha();
        $submit = new Site_Form_Element_Submit();
        //$code = new Site_Form_Element_CommonHash();
        //$code->setTimeout(7200);
        $page = new Site_Form_Element_CommonHidden(PAGE);        
        
        $this->addElement($name)
             ->addElement($comment)
             ->addElement($captcha)
             ->addElement($submit)
             //->addElement($code)
             ->addElement($page);
    }

    public function setUsername($user){
        $this->getElement(NAME)->setValue($user->name);
    }

    public function setPage($uri) {
        $this->setDefault(PAGE, $uri);
    }

    public function copy(Site_Model_Comment  $model){
        $name=$this->getValue(NAME);
        $text=$this->getValue(TEXT);
        $date = new Zend_Date();
        if(!get_magic_quotes_gpc()){
            $name=addslashes($name);
            $text=addslashes($text);
        }
        $model->name = $name;
        $model->text = $text;
        $model->date = $date->toString('YYYY-MM-dd HH:mm:ss');
        $model->ip = $_SERVER['REMOTE_ADDR'];
    }


}
