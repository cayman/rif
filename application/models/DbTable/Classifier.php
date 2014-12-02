<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zakirov
 * Date: 15.03.2010
 * Time: 18:12:54
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_DbTable_Classifier extends Zend_Db_Table_Abstract {
 
    protected $_name = 'taxonomy_classifier';
    protected $_primary = 'id';

    protected $_dependentTables = array('Site_Model_DbTable_Term',
                    'Site_Model_DbTable_TypeClassifier','Site_Model_ClassifierAccess');


    /**
     * Возвращает классификаторы для типа узла  + настройки
     * @param  int|array $node
     * @param  $role Роль пользователя
     * @return Zend_Db_Select
     */
    public function selectByTypeId($type) {
        $select = $this->select()->setIntegrityCheck(false)
                ->from("{$this->_name} as TC")
                ->join("node_type_classifier as NTC", "NTC.class_id = TC.id",
                array('type_id' => 'NTC.type_id',
                      'term_required' => 'NTC.required',
                      'term_default' => 'NTC.default_term_id'))
                ->where('NTC.type_id in (?)', $type)
                ->order(array("TC.order"));

        //Log::dubug($this, "select", $select);
        return $select;
    }

    /**
     * Возвращает классификаторы для типа узла  + настройки
     * @param  int|array $node
     * @param  $role Роль пользователя
     * @return Zend_Db_Select
     */
    public function selectByCode($code) {
        $select = $this->select()->setIntegrityCheck(false)
                ->from("{$this->_name} as TC")
                ->where('TC.code = ?', $code);

        //Log::dubug($this, "select", $select);
        return $select;
    }
}
