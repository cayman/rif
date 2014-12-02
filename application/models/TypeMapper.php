<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 29.09.2010
 * Time: 18:26:35
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_TypeMapper extends Site_Model_EntityMapper {

    protected $_modelClass = "Site_Model_Type";
    protected $_dbTableClass = "Site_Model_DbTable_Type";
    protected $_dbSubTableClass = "Site_Model_DbTable_Classifier";



    /*--------------------Переопределение базовых методов-------------------------*/
    /**
     * Переопределение базового метода
     * @param  $select
     * @return Site_Model_Entity[]
     */
    protected function fetchAll($select = null) {
        $list = parent::fetchAll($select);
        if (count($list) > 0) {
            $ids = array_keys($list);
            Log::debug($this, "selectByNodeIds:", $ids);
            $select = $this->getDbSubTable()->selectByTypeId($ids);
            $result = $this->getDbSubTable()->fetchAll($select);
            foreach ($result as $row) {
                $list[$row->type_id]->addClassifier($row->code, $row);
            }
        }
        return $list;
    }
    /**
     * Переопределение базового метода
     * @param  $query
     * @return Site_Model_Entity
     */
    protected function fetchOne($query) {
        $type = parent::fetchOne($query);
        if (isset($type)) {
            $select = $this->getDbSubTable()->selectByTypeId($type->id);
            $result = $this->getDbSubTable()->fetchAll($select);
            foreach ($result as $row) {
                $type->addClassifier($row->code, $row);
            }
        }
        return $type;
    }

}
