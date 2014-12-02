<?php

class ServiceController extends Zend_Controller_Action {

    private $soap = null;

    public function init() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        $this->soap = array('soap_version' => SOAP_1_2, 'encoding' => 'UTF-8');
    }


    /**
     * Вызов веб сервиса
     */
    public function callAction() {
        //Определяем название сервиса и название класса
        $name = $this->_getParam(NAME);
        $server = new Zend_Soap_Server($this->_helper->serviceUri($name, WSDL), $this->soap);
        $server->setClass("Site_Service_Node");
        $server->handle();
    }

    public function wsdlAction() {
        //Определяем название сервиса и название класса
        $name = $this->_getParam(NAME);
        $autodiscover = new Zend_Soap_AutoDiscover('Zend_Soap_Wsdl_Strategy_ArrayOfTypeComplex', $this->_helper->serviceUri($name));
        $autodiscover->setClass("Site_Service_Node");
       // $autodiscover->setComplexTypeStrategy($strategy)
       // $autodiscover->addComplexType($type)
        $autodiscover->handle();
    }


    public function cleanAction() {
        Log::info($this, 'clean cache', $this->_getAllParams());
        $db = $this->getInvokeArg('cache_db');
        $core = $this->getInvokeArg('cache_core');
        $db->clean(Zend_Cache::CLEANING_MODE_ALL);
        $core->clean(Zend_Cache::CLEANING_MODE_ALL);
        $this->view->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
        $this->_helper->Messenger('Cash is clear');
        $this->_helper->Goto->back();
    }

}