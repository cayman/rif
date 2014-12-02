<?php
class Site_Service_CleanIP implements Zend_Acl_Assert_Interface
{
    const BAD_IP_COUNT=3;

    public function __construct(){

    }


    public function assert(Zend_Acl $acl,
                           Zend_Acl_Role_Interface $role = null,
                           Zend_Acl_Resource_Interface $resource = null,
                           $privilege = null)
    {
        return $this->_isCleanIP($_SERVER['REMOTE_ADDR']);
    }

    protected function _isCleanIP($ip)
    {
        if($ip=='127.0.0.1')
            $count = 0;
        else
            $count=Site_Service_Registry::get(COMMENT)->countCommentsSpam("IP:$ip");
        $isGood=($count<=self::BAD_IP_COUNT);
        Log::info($this,"IP:$ip = is good? $isGood . Bad comment=$count");
        return $isGood;
    }
}