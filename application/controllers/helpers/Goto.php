<?php
/**
 * Помощник действия для загрузки логина
 *
 * @uses Zend_Controller_Action_Helper_Abstract
 */
class Site_Controller_Helper_Goto extends Zend_Controller_Action_Helper_Redirector
{

    public function route($controller,$id=null,$action=_OPEN)
    {
        Log::debug($this,"goto=$controller,$action",$id);
        $this->gotoRoute(array(CONTROLLER=>$controller,
                               ACTION=>$action,
                               ID=>$id
                               //PAGE=>$page
                               ),$controller);
    }

    public function back() {

        if(isset($_SERVER[HTTP_REFERER]))
          $this->gotoUrl($_SERVER[HTTP_REFERER]);
        else
          $this->route(MAIN);
    }


    /**
     * Паттерн Стратегии: вызываем помощник как метод брокера
     *
     * @param  string $name
     * @param  array|Zend_Config $options
     * @return Zend_Form
     */
    public function direct($controller,$id,$action=_OPEN)
    {
        $this->route($controller,$id,$action);
    }
}
