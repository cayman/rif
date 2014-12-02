<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 27.09.2010
 * Time: 14:27:06
 * To change this template use File | Settings | File Templates.
 */

class Site_Service_Type extends Site_Service_Abstract  {

    protected $_types;


     /**
     * Return type by ID
     * @throws Site_Service_Exception
     * @param  int $id
     * @return Site_Model_Type
     */
    public function getType($id) {
        try {
            $type = $this->getTypeMapper()->find($id);
            if (empty($type)) throw new Site_Service_Exception('Type not found',$id, 3001);
            return $type;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getType',3002,$e);
        }
    }

    /**
     * Return all types
     * @throws Site_Service_Exception
     * @return Site_Model_Type[]
     */
    public function getTypes() {
        try {
            if($this->_types === null){
                $cache = Zend_Registry::get(CACHE_CORE);
                if (!$this->_types = $cache->load('types')) {
                    $this->_types = $this->getTypeMapper()->findAll();
                    $cache->save($this->_types, 'types', array('types'));
                }
            }
            return $this->_types;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getTypes',3003,$e);
        }
    }

    /**
     * Return all types as array
     * @throws Site_Service_Exception
     * @return String[]
     */
    public function getTypesOptions() {
        $typeOptions = array();
        foreach ($this->getTypes() as $type) {
            $typeOptions[$type->id] = $type->name;
        }
        return $typeOptions;
    }


    /**
     * Append new type
     * @throws Site_Service_Exception
     * @param  Site_Model_Type $type
     * @return int $id
     */
    public function addType($type) {
        try {
            $id = $this->getTypeMapper()->create($type);
            if (!is_numeric($id)) throw new Site_Service_Exception('Type not saved',null,3004);
            return $id;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'addType',3005,$e);
        }
    }

    /**
     * Save change in type
     * @throws Site_Service_Exception
     * @param  Site_Model_Type $type
     * @return null
     */
    public function editType($type) {
        try {
            if(!$this->getTypeMapper()->modify($type))
               throw new Site_Service_Exception('Type not saved',null, 3006);
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'editType',3007,$e);
        }
    }



        /**
         * @return string
         */
        public function about() {
            return 'Node type service';
        }


}
