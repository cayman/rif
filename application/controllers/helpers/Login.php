<?php
/**
 * Помощник действия для загрузки логина
 *
 * @uses Zend_Controller_Action_Helper_Abstract
 */
class Site_Controller_Helper_Login extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Loader_PluginLoader
     */
    protected $auth;
    protected $acl;
    /**
     * Конструктор: инициализирует плагин загрузки
     *
     * @return void
     */
    public function __construct()
    {
        $this->auth = Site_Service_Registry::get(ACCESS);
        $this->acl = Zend_Registry::get(SITE_ACL);
        Log::debug($this,"construct");
    }

    /**
     * Загружает форму с выбранными опциями
     *
     * @param  string $name
     * @param  array|Zend_Config $options
     * @return Zend_Form
     */
    public function login()
    {
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
    
    public function hasResource($resource){
        return $this->acl->has($resource);
    }

    /**
     * Паттерн Стратегии: вызываем помощник как метод брокера
     *
     * @param  string $name
     * @param  array|Zend_Config $options
     * @return Zend_Form
     */
    public function direct()
    {
        return $this->login();
    }
}
