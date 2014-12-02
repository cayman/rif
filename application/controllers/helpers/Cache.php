<?php
/**
 * Помощник действия для загрузки логина
 *
 * @uses Zend_Controller_Action_Helper_Abstract
 */
class Site_Controller_Helper_Cache extends Zend_Controller_Action_Helper_Abstract
{
    protected $role;

    public function init()
    {
        $this->role = Site_Service_Registry::get(ACCESS)->getRole();
    }

    public function isCached($name,$tags,$useRole=true) {
        $action=$this->getActionController();
        $action->view->cacheName =$useRole ? $this->role.'_'.$name:$name;
        $action->view->cacheTags =is_array($tags)?$tags:array($tags);

        $isCached = $action->view->cache->test($action->view->cacheName);
        Log::debug($this, $name . " has cached? $isCached");
        return  $isCached;
    }


    /**
     * Паттерн Стратегии: вызываем помощник как метод брокера
     *
     * @param  string $name
     * @param  array|Zend_Config $options
     * @return Zend_Form
     */
    public function direct($name,$tags,$role=true)
    {
        $this->isCached($name,$tags,$role);
    }
}
