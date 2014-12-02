<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 29.09.2010
 * Time: 18:26:35
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_ClassifierMapper extends Site_Model_EntityMapper  {

    protected $_modelClass = "Site_Model_Classifier";
    protected $_dbTableClass = "Site_Model_DbTable_Classifier";
    protected $_dbSubTableClass = "Site_Model_DbTable_ClassifierAccess";


     /**
     * Возвращает классификатор по коду
     * @param  $code
     * @return Site_Model_Classifier
     */
    public function findByCode($code) {
        Log::debug($this, "findByCode:", $code);
        $select = $this->getDbTable()->selectByCode($code);
        return $this->fetchOne($select);
    }


    /**
     * Возврашает доступные классификаторы для роли
     * @param  $role
     * @return int[]
     */
    public function findByRole($role) {
        Log::debug($this,"findByRole:",$role);
        $select = $this->getDbSubTable()->selectByRole($role);
        $result = $this->getDbSubTable()->fetchAll($select);
        $list=array();
        foreach($result as $row){
            $list[$row->class_id] = $row->class_id;
        }
        return $list;
    }


}
