<?php
/**
 * Базовый класс мапперов, содержит основнуюд функциональность запросов к БД
 * User: Zakirov
 * Date: 27.09.2010
 * Time: 15:23:48
 * To change this template use File | Settings | File Templates.
 */

abstract class Site_Model_EntityMapper
{
    protected $_modelClass;
    protected $_dbTableClass;
    protected $_dbSubTableClass;
    protected $_dbTable;
    protected $_dbSubTable;
    protected $_registry;

    final function __construct($registry, $cache = null)
    {
        $this->_registry = $registry;
        if (is_string($this->_dbTableClass))
            $this->_dbTable = $this->initDbTable($this->_dbTableClass, $cache);
        else
            throw new Zend_Exception('Invalid table data gateway provided', 12);
        if (is_string($this->_dbSubTableClass))
            $this->_dbSubTable = $this->initDbTable($this->_dbSubTableClass, $cache);
        $this->init();
        return $this;
    }

    /*-----Public methods--------*/

    /**
     * Возвращает модель по ID
     * @param  $id
     * @return Site_Model_Entity
     */
    public function find($id)
    {
        Log::debug($this, "find:", $id);
        $result = $this->getDbTable()->findById($id);
        return $this->fetchOne($result);
    }

    /**
     * Возврашает все модели
     * @return Site_Model_Entity[]
     */
    public function findAll()
    {
        return $this->fetchAll();
    }


    /**
     * Модификация термина в БД, (метод возможно переопределять)
     * @param  Site_Model_Entity $model
     * @return boolean
     */
    public function modify(Site_Model_Entity $model)
    {
        if (isset($model->id))
            return $this->update($model) > 1;
        else
            return false;
    }


    /**
     * Создание термина в БД, (метод возможно переопределять)
     * @param  Site_Model_Entity $model
     * @return int $id
     */
    public function create(Site_Model_Entity $model)
    {
        return $this->insert($model);
    }


    /*-----Protectes methods--------*/
    /**
     * Первоначальная инициализация Маппера,
     * Конструктор не наследуем
     */
    protected function init()
    {
    }

    protected function initDbTable($dbTableClass, $cache)
    {
        if (isset($cache))
            return new $dbTableClass(array('metadataCache' => $cache));
        else
            return new $dbTableClass();
    }

    /**
     * Возвращает количество, д.б поле count
     * @param  $select
     * @return int
     */
    protected function fetchCount($select)
    {
        $result = $this->getDbTable()->fetchAll($select);
        if (0 == count($result)) {
            return 0;
        }
        $row = $result->current();
        Log::debug($this, 'count', $row->count);
        return isset($row->count) ? $row->count : 0;
    }

    /**
     * Возвращает одну модель
     * @param  Zend_Db_Table_Rowset_Abstract|Zend_Db_Select $query
     * @return Site_Model_Entity
     */
    protected function fetchOne($query)
    {
        if ($query instanceof Zend_Db_Table_Rowset_Abstract)
            $result = $query;
        else {
            Log::debug($this, "select:", $query);
            $result = $this->getDbTable()->fetchAll($query);
        }
        if (0 == count($result)) {
            return null;
        }
        $row = $result->current();
        return $this->createModel($row);
    }

    /**
     * Возвращает массив моделей соответствующие запросу
     * @param  Zend_Db_Table_Rowset_Abstract|Zend_Db_Select $query
     * @return Site_Model_Entity[]
     */
    protected function fetchAll($query = null)
    {
        if ($query instanceof Zend_Db_Table_Rowset_Abstract)
            $result = $query;
        else {
            Log::debug($this, "select:", $query);
            $result = $this->getDbTable()->fetchAll($query);
        }
        $list = array();
        if (count($result) > 0) {
            foreach ($result as $row) {
                $list[$row->id] = $this->createModel($row);
            }
        }
        return $list;
    }


    /**
     * Изменение данных модели
     * @param  Site_Model_Entity $model
     * @return int $count
     */
    protected function update(Site_Model_Entity $model)
    {
        $data = $model->toArray();
        $ret = $this->getDbTable()->update($data, array('id = ?' => $model->id));
        Log::info($this, "update:{$model->id}:$ret", $data);
        return $ret;
    }


    /**
     * Создание данных модели
     * @param  Site_Model_Entity $model
     * @return int $id
     */
    protected function insert($model)
    {
        $data = $model->toArray();
        return $this->getDbTable()->insert($data);
    }

    /**
     * Вернуть основную таблицу БД для модели
     */
    protected function getDbTable()
    {
        return $this->_dbTable;
    }

    /**
     * Вернуть дополнительную таблицу модели
     */
    protected function getDbSubTable()
    {
        return $this->_dbSubTable;
    }

    /**
     * Вернуть Адаптер БД
     */
    protected function getDbAdapter()
    {
        $this->getDbTable()->getAdapter();
    }

    /**
     * Создание модели (метод для переопределения)
     * @param  array|row $data
     * @return Site_Model_Entity
     */
   final protected function createModel($data){
        if (is_string($this->_modelClass))
            return new $this->_modelClass($data);
        else
            throw new Zend_Exception('Invalid model class', 16);
    }
}
