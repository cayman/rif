<?php
/**
 * Помощник действия для загрузки логина
 *
 * @uses Zend_Controller_Action_Helper_Abstract
 */
class Site_Controller_Helper_Goto extends Zend_Controller_Action_Helper_Redirector
{

    public function route($controller, $id = null, $action = _OPEN)
    {
        Log::debug($this, "goto=$controller,$action", $id);
        $this->gotoRoute(array(CONTROLLER => $controller,
            ACTION => $action,
            ID => $id
            //PAGE=>$page
        ), $controller);
    }

    public function back()
    {

        if (isset($_SERVER[HTTP_REFERER]))
            $this->gotoUrl($_SERVER[HTTP_REFERER]);
        else
            $this->route(MAIN);
    }

    /**
     * direct(): Perform helper when called as
     * $this->_helper->redirector($action, $controller, $module, $params)
     *
     * @param  string $action
     * @param  string $controller
     * @param  string $module
     * @param  array  $params
     * @return void
     */
    public function direct($action, $controller = null, $module = null, array $params = array())
    {
        $this->gotoSimple($action, $controller, $module, $params);
    }

}
