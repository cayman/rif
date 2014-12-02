<?php
class Site_Plugin_Acl extends Zend_Controller_Plugin_Abstract {
    private $_auth = null;
    private $_goto = null;
    private $_messenger = null;
    protected $allow = false;

    public function __construct() {
        $this->_auth = Zend_Controller_Action_HelperBroker::getStaticHelper('Login');

        $this->_goto = Zend_Controller_Action_HelperBroker::getStaticHelper('Goto');
        $this->_messenger = Zend_Controller_Action_HelperBroker::getStaticHelper('Messenger');
    }

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request){
        if ($request->getControllerName() != ERROR) {
            $role = $this->_auth->getRole();
            $resource = $request->getControllerName();
            $privilege = $request->getActionName();
            $req = urldecode($request->getRequestUri());
            Log::path($role,$req,"$resource->$privilege",Zend_Log::NOTICE);
            Log::info($this, "$req ", array(
                'role' => $role,
                'resource' => $resource,
                'privilege' => $privilege,
                'param' => $request->getParams()));


            //@name find resource
            if (!$this->_auth->hasResource($resource)) {
                $this->_messenger->addMessage('Page is missing');
                Log::err(null,"Page is missing");
                $this->_goto->route(MAIN);
                //$this->_response->setRedirect('/');
            }
            //@name access for resource
            $this->allow=$this->_auth->isAllowed($resource, $privilege);
            if (!$this->allow) {
                // Если недостаточно прав то мы перенаправляем его на страницу авторизации
                Log::err($this, "role='$role' hasn't rite to resource='$resource' privilege='$privilege'");
                $this->_messenger->addMessage('Access to the page is closed');
                $this->_goto->route(MAIN);

            } else {
                Log::debug($this, "role='$role' has rite to resource='$resource' privilege='$privilege'");
            }

        }
    }


    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        if ($request->getControllerName() != ERROR) {
            Log::debug($this, "preDispatch:");
            if(!$this->allow){
                $role = $this->_auth->getRole();
                $resource = $request->getControllerName();
                $privilege = $request->getActionName();
                $this->allow=$this->_auth->isAllowed($resource, $privilege);
                Log::debug($this, "allow={$this->allow} ($resource/$privilege)");
                if (!$this->allow){
                  $request->setControllerName(BLOCK)->setActionName('empty');
                }
            }
            $this->allow=false;
        }
    }

}
