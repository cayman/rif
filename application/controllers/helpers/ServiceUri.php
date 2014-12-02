<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 27.09.2010
 * Time: 17:59:42
 * To change this template use File | Settings | File Templates.
 */
 

class Site_Controller_Helper_ServiceUri extends Zend_Controller_Action_Helper_Abstract
{


    /**
     * Detect and returns the service url
     * @param string $name
     * @param string $action
     * @return string
     *
     */
    public function serviceUri($name,$action)
    {
        $urlOptions=array('action'=>$action,'controller'=>SERVICE,NAME=>$name);
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $url = $router->assemble($urlOptions, SERVICE, true, true);
        return  $this->getSchema() . '://' . $this->getHostName() . $url;
    }

    /**
     * Detect and returns the current HTTP/HTTPS Schema
     * @return string
     *
     */
    protected function getSchema() {
        $schema = "http";
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $schema = 'https';
        }
        return $schema;
    }

    /**
     * Detect and return the current hostname
     * @return string
     *
     */
    protected function getHostName() {
        if (isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        } else {
            $host = $_SERVER['SERVER_NAME'];
        }
        return $host;
    }
    /**
     * Паттерн Стратегии: вызываем помощник как метод брокера
     *
     * @param  string $name
     * @param  array|Zend_Config $options
     * @return Zend_Form
     */
    public function direct($name,$action=CALL)
    {
        return $this->serviceUri($name,$action);
    }
}