<?php
class Site_View_Helper_Login extends Zend_View_Helper_Abstract{

    private $auth;
    private $acl;

    public function login() {
        $this->auth = Site_Service_Registry::get(ACCESS);
        $this->acl = Zend_Registry::get(SITE_ACL);
        return $this;
    }

    public function hasIdentity(){
        return $this->auth->hasIdentity();
    }

    public function getUser()
    {
        return $this->auth->getUser();
    }

    public function getRole()
    {
        return $this->auth->getRole();
    }


    public function isAllowed($resource, $privilege) {
        $allow=$this->acl->isAllowed($this->getRole(), $resource, $privilege);
        //Log::debug($this,"isAllowed($resource, $privilege)",$allow);
        return $allow;
    }


    public function is($role) {
        return $this->is($role);
    }

}