<?php
class Site_Form_Auth extends Zend_Form
{
private $_session;

    public function __construct($action)
    {
        parent::__construct();
        Log::info($this,'actionUrl',$action);
        $this->setAction($action);
        $this->addDecorator('FormErrors');
    }

    public function init()
    {
        parent::init();
        $this->_session = new Zend_Session_Namespace(LOGIN);
        if(!isset($this->_session->attempt))
            $this->_session->attempt=0;
        Log::debug($this,"login attempt",$this->_session->attempt);
        $this->setMethod('post')
             ->setAttrib('class', 'auth_form')
             ->setAttrib('id','auth_form');
        $login = new Site_Form_Element_AuthLogin(LOGIN);
        $password = new Site_Form_Element_AuthPassword(PASSWORD);
        $submit = new Site_Form_Element_Submit();
        //$code = new Site_Form_Element_CommonHash();
        //$code->setTimeout(100);
        $page = new Site_Form_Element_CommonHidden(PAGE);        

        $this->addElement($login)
             ->addElement($password);
        if($this->_session->attempt>3)
            $this->addElement(new Site_Form_Element_CommentCaptcha());
        $this->addElement($submit)
             //->addElement($code)
             ->addElement($page);
    }



    public function attempt($value=-1){
        if($value>=0) $this->_session->attempt=$value;
        else $this->_session->attempt++;
        Log::debug($this,"login attempt",$this->_session->attempt);
    }

    public function getLogin(){
        return  $this->getValue(LOGIN);
    }
    public function getPassword(){
        return $this->getValue(PASSWORD);
    }
}
