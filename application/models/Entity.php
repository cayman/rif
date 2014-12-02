<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 30.09.2010
 * Time: 10:22:57
 * To change this template use File | Settings | File Templates.
 */

abstract class Site_Model_Entity {

    protected $_id;

    public function __construct($data = null) {
        if (!is_null($data)) {
            $this->import($data);
        }
    }

    public function import($data) {
        if ($data instanceof Zend_Db_Table_Row_Abstract)
            $this->importRow($data);
        else
            foreach ($data as $key => $value)
                $this->__set($key, $value);
        return $this;
    }


    /**
     * Загрузка модели из поля строки select
     * Данную функцию можно переопределять,
     * если поля модели не соответствуют поля строки выборки  select
     * @param  Zend_Db_Table_Row_Abstract $row
     * @return Site_Model_Entity
     */
    public function importRow(Zend_Db_Table_Row_Abstract $row) {
        foreach ($row->toArray() as $key => $value)
           // if(!in_array($key,$exclude))
                $this->__set($key, $value);
        return $this;
    }


    public function toArray() {
        $data=array();
        foreach (get_object_vars($this) as $key => $value)
            $data[$this->getPropertyName($key)]= $value;
        return $data;
    }
    public function __isset($name) {
        $property = "_$name";
        if (property_exists($this, $property))
            return isset($this->$property);
        else
            return false;
    }

    public function __set($name, $value) {
        $property = "_$name";
        if (!property_exists($this, $property)) {
            $class = get_class($this);
           // var_dump("You cannot set new properties $name on this $class object");
        }else
            $this->$property = $value;
    }

    public function __get($name) {
        $property = "_$name";
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
    public function getPropertyName($property) {
        if (property_exists($this, $property)) {
            return substr($property,1);
        }
    }




    public function __unset($name) {
        $property = "_$name";
        if (isset($this->$property)) {
            unset($this->$property);
        }
    }

//    public function __call($method, $arguments) {
//        if (('mapper' == $method) || !method_exists($this, $method)) {
//            throw new Site_Service_Exception('Invalid property', 15);
//        }
//        return $this->$method($arguments);
//    }

    /**
     * Store table, primary key and data in serialized object
     *
     * @return array
     */
//    public function __sleep()
//    {
//        return array_keys(get_object_vars($this));
//    }

    /**
     * Setup to do on wakeup.
     * A de-serialized Row should not be assumed to have access to a live
     * database connection, so set _connected = false.
     *
     * @return String
     */
//    public function __wakeup()
//    {
//        $this->_connected = false;
//    }

    function __toString() {
        $str = get_class($this) . " object {\n";
        $str .= print_r(get_object_vars($this),true);
        $str .= "};";
        return $str;
    }

}
