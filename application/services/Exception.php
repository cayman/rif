<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 29.09.2010
 * Time: 12:32:15
 * To change this template use File | Settings | File Templates.
 */
 
class Site_Service_Exception extends Exception {
    protected $previous;
    protected $translator;
    protected $redirect;
    protected $isOld;
    public function __construct($msg, $params, $code, Exception $previous = null,$redirect=null) {
        $msg=$this->inject($msg,$params);
        $this->isOld=version_compare(PHP_VERSION, '5.3.0', '<');
        if ($this->isOld) {
            parent::__construct($msg, (int) $code);
            $this->_previous = $previous;
        } else {
            parent::__construct($msg, (int) $code, $previous);
        }
        $this->redirect = $redirect;
        Log::err($this,$msg,$previous);
        Log::err($this,$this->redirect);
    }


    private function inject($msg, $params=null){
        if(Zend_Registry::isRegistered('Zend_Translate')){
           $translator= Zend_Registry::get('Zend_Translate');
           return (isset($params))?sprintf($translator->_($msg),$params):$translator->_($msg);
        }
        else
           return (isset($params))?sprintf($msg,$params):$msg;
    }


    public function getPreviousException()
    {
       if ($this->isOld)
         return $this->previous;
       else
         return $this->getPrevious();
    }


    public function getRedirect()
    {
        return $this->redirect;
    }

   public function isRedirect()
    {
        return isset($this->redirect);
    }

}
