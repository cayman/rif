<?php
/**
 * Реестр сервисов
 * Реализует шаблон Registry
 * Реализует шаблон Singltone
 * User: Rustem
 * Date: 01.10.2010
 * Time: 22:13:40
 * To change this template use File | Settings | File Templates.
 */

class Site_Service_Registry {
   protected static $_instance = null;

   public static function getInstance(){
        if(self::$_instance === null)
            self::$_instance = new Site_Service_Registry();
        return self::$_instance;
   }

   public static function get($name){
       return Site_Service_Registry::getInstance()->service($name);
   }

    /**
     * Возвращает имя сервиса
     * @throws Site_Service_Exception
     * @param $name
     * @return
     */
   public function service($name){
        $className="Site_Service_".ucfirst($name);
        if(!isset($this->_services[$className]))
            if(class_exists($className) && get_parent_class($className)=='Site_Service_Abstract')
               $this->_services[$className] = new $className($this);
            else
               throw new Site_Service_Exception("Service not exist",$name,1001);

        return $this->_services[$className];
   }

    /**
     * Возвращает объект маппера по его имени
     * @throws Site_Service_Exception
     * @param $name
     * @return
     */
    public function mapper($name) {
        $className="Site_Model_".ucfirst($name).'Mapper';
        if(!isset($this->_mappers[$className]))
        if (class_exists($className) && get_parent_class($className)=='Site_Model_EntityMapper')
               $this->_mappers[$className] = new $className($this);
            else
               throw new Site_Service_Exception("Mapper not exist",$name ,1002);
        return $this->_mappers[$className];
    }

    private $_mappers;
    private $_services;
}
