<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Rustem
 * Date: 18.09.2010
 * Time: 19:26:54
 * To change this template use File | Settings | File Templates.
 */
 
class Site_Controller_Helper_Messenger extends Zend_Controller_Action_Helper_FlashMessenger{

    protected $translator;

    public function addMessage($message,$param=null) {
        $this->translator = Zend_Registry::get('Zend_Translate');
        if(isset($param))
            return parent::addMessage(sprintf($this->translator->_($message),$param));
        else return parent::addMessage($this->translator->_($message));
    }

    /**
     * Strategy pattern: proxy to addMessage()
     *
     * @param  string $message
     * @return void
     */
    public function direct($message,$param=null)
    {
        return $this->addMessage($message,$param);
    }
}
