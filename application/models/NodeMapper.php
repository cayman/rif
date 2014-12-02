<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 27.09.2010
 * Time: 15:23:48
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_NodeMapper extends Site_Model_EntityMapper
{

    protected $_access;
    protected $_modelClass = "Site_Model_Node";
    protected $_dbTableClass = "Site_Model_DbTable_Node";
    protected $_dbSubTableClass = "Site_Model_DbTable_NodeTerm";


    protected function init()
    {
        $this->_access = $this->_registry->service(ACCESS);
    }

    /**
     * Возвращает один узел, с учетом прав доступа
     * Переопределение базового метода
     * @param  $id
     * @return Site_Model_Node $node
     */
    public function find($id)
    {
        Log::debug($this, "find:", $id);
        $termsAccess = $this->_access->getLegalTerms();
        $result = $this->getDbTable()->find($id, $termsAccess);
        return $this->fetchOne($result);
    }

    /**
     * Возвращает один узел, с учетом прав доступа
     * Переопределение базового метода
     * @param  $id
     * @return Site_Model_Node $node
     */
    public function findNext($date,$term)
    {
        Log::debug($this, "find:", $term);
        $termsAccess = $this->_access->getLegalTerms();
        $result = $this->getDbTable()->findNext($date,$term,$termsAccess);
        return $this->fetchOne($result);
    }

    /**
     * Возвращает один узел, с учетом прав доступа
     * Переопределение базового метода
     * @param  $id
     * @return Site_Model_Node $node
     */
    public function findPrev($date,$term)
    {
        Log::debug($this, "find:", $term);
        $termsAccess = $this->_access->getLegalTerms();
        $result = $this->getDbTable()->findPrev($date,$term,$termsAccess);
        return $this->fetchOne($result);
    }

    /**
     * Возврашает последние (по дате) узлы для каждого термина - c учетом прав доступа
     * @param  array $terms Термины таксономии
     * @param  int $count Количестов для каждого термина
     * @return select;
     */
    public function findLastByTerms($terms, $count = 2)
    {
        Log::debug($this, "findLastByTerms:", $terms);
        $termsAccess = $this->_access->getLegalTerms();
        $select = $this->getDbTable()->selectLastByTerms($terms, $termsAccess, $count);
        return $this->fetchAll($select);
    }

    /**
     * Возврашает количество узлов для заднной таксономии (и строки поиска) - c учетом прав доступа
     * @param  array $terms Термины таксономии
     * @param  string $keys Строка поиска *
     * @return int;
     */
    public function countByTerms($terms, $keys)
    {
        Log::debug($this, "countdByTerms:", $terms);
        $termsAccess = $this->_access->getLegalTerms();
        $select = $this->getDbTable()->countByTerms($terms, $termsAccess, $keys);
        return $this->fetchCount($select);
    }

    /**
     * Возврашает количество узлов для заднного типа - (c учетом прав доступа и без )
     * @param  int $type Тип узла
     * @param  boolean $useLegalTerms - учитывать права доступа или нет
     * @return int;
     */
    public function countByType($type, $useLegalTerms = true)
    {
        $termsAccess = ($useLegalTerms) ? $this->_access->getLegalTerms() : null;
        $select = $this->getDbTable()->countByType($type, $termsAccess);
        $num = $this->fetchCount($select);
        return $num;
    }

    /**
     * Возврашает узлы для заднной таксономии (и строки поиска)  - c учетом прав доступа
     * @param  array $terms Термины таксономии
     * @param  array  $termsAccess Термины досттупа к материалу
     * @param  string $keys Строка поиска *
     * @param  string $order Поле сортировки
     * @param  string $direction Направление сортировки
     * @param  int $offset Смещение в результатах запроса
     * @param  int $itemCount Макс количество возвращаемых узлов
     * @return Site_Model_Node[]
     */
    public function findByTerms($terms, $keys, $order = null, $direction = null, $offset = 0, $itemCount = null)
    {
        Log::debug($this, "findByTerm:", $terms);
        $termsAccess = $this->_access->getLegalTerms();
        $select = $this->getDbTable()->selectByTerms($terms, $termsAccess, $keys, $order, $direction);
        if (isset($itemCount)) $select->limit($itemCount, $offset);
        return $this->fetchAll($select);
    }

    /**
     * Возврашает случайны узел для заднного типа - c учетом прав доступа
     * @param  int $type
     * @return Site_Model_Node
     */
    public function findRandom($type)
    {
        $termsAccess = $this->_access->getLegalTerms();
        $select = $this->getDbTable()->countByType($type, $termsAccess);
        $num = $this->fetchCount($select);
        $rnd = rand(1, $num);
        Log::debug($this, "findRandom: $rnd");
        $select = $this->getDbTable()->selectByType($type, $termsAccess, $rnd);
        return $this->fetchOne($select);
    }

    /**
     * Проверка идентияности узла, возвращает список аналогий
     * @param  int $node Идентификатор проверяемого узла
     * @param  string[] $keys список ключевых слов узла
     * @param  int $count Количестов слов для проверки идентичности
     * @return Site_Model_Node[]
     */
    public function findIdentity($id, $keys, $count)
    {
        log::debug($this, 'findIdentity', $keys);
        $keys = array_filter($keys, 'not_empty_string');
        $keys = array_slice($keys, 0, $count);
        $select = $this->getDbTable()->selectByKeys($keys, $id);
        return $this->fetchAll($select);
    }


    /**
     * Сохранение терминов таксономии узла в БД
     * @param  int $id
     * @param  Site_Model_Term[] $terms
     * @return int $id
     */
    public function saveTerms(Site_Model_Node $model)
    {
        Log::info($this, 'saveTerms:' . $model->id);
        try {
            $this->getDbSubTable()->getAdapter()->beginTransaction(); //@todo адаптер не назодит
            $classifierAccess = $this->_access->getLegalClassifier();
            $mapper = $this->_registry->mapper(TERM);
            $this->getDbSubTable()->delete("node_id = {$model->id}");
            Log::debug($this, 'delete node_terms node_id =' . $model->id);
            foreach ($model->terms as $terms) {
                foreach ($terms as $term) {
                    Log::debug($this, 'analise term =' . $term);
                    if (!isset($term->id)) { //Создаем или ищем термин
                        $search_term = $mapper->findByName($term->name, $term->class_id);
                        if (isset($search_term))
                            $term->id = $search_term->id; // найден
                        else
                            $term->id = $mapper->create($term); // не найден термин значит создаем
                    }
                    $data = array('node_id' => $model->id, 'term_id' => $term->id, 'term_value' => $term->term_value);
                    $this->getDbSubTable()->insert($data);
                    Log::debug($this, 'inserted ' . $data);
                }
            }
            $this->getDbSubTable()->getAdapter()->commit();
            return true;
        } catch (Exception $e) {
            $this->getDbSubTable()->getAdapter()->rollback();
            throw new Site_Service_Exception('Terms not saved', $model->id, 3016, $e);
        }
    }


    public function addTermValue($id, $term_id, $value)
    {

        $result = $this->getDbSubTable()->find($id, $term_id);
        if (count($result) > 0)
            $row = $result->current();
        else
            $row = $this->getDbSubTable()->createRow(array('node_id' => $id, 'term_id' => $term_id, 'term_value' => 0));
        try {
            if ($value == -1 || $value == 1) {
                $this->getDbSubTable()->getAdapter()->beginTransaction();
                $row->term_value += $value;
                if ($row->term_value != 0)
                    $row->save();
                else
                    $row->delete();
                $this->getDbSubTable()->getAdapter()->commit();
            }
        }
        catch (Exception $e) {
            $this->getDbSubTable()->getAdapter()->rollback();
            throw new Zend_Db_Table_Row_Exception($e->getMessage());
        }
        return $row->term_value;
    }


    /*--------------------Переопределение базовых методов-------------------------*/
    /**
     * Возвращает все узелы соответствующие запросу - с учетом прав доступа
     * Переопределение базового метода
     * @param  $select Zend_Select
     * @return Site_Model_Node[]
     */
    protected function fetchAll($select = null)
    {
        $list = parent::fetchAll($select);
        if (count($list) > 0) {
            $ids = array_keys($list);
            Log::debug($this, "selectByNodeIds:", $ids);
            $classifierAccess = $this->_access->getLegalClassifier();
            $select = $this->getDbSubTable()->selectByNode($ids, $classifierAccess);
            $result = $this->getDbSubTable()->fetchAll($select);
            foreach ($result as $row) {
                $list[$row->node_id]->addTerm($row->class_code, $row);
            }
        }
        return $list;
    }


    /**
     * Возвращает один узел по запросу - с учетом прав доступа
     * Переопределение базового метода
     * @param  Zend_Db_Table_Rowset_Abstract|Zend_Db_Select $query
     * @return Site_Model_Node[]
     */
    protected function fetchOne($query = null)
    {
        $node = parent::fetchOne($query);
        if (isset($node)) {
            $classifierAccess = $this->_access->getLegalClassifier();
            $select = $this->getDbSubTable()->selectByNode($node->id, $classifierAccess);
            $result = $this->getDbSubTable()->fetchAll($select);
            foreach ($result as $row) {
                $node->addTerm($row->class_code, $row);
            }
        }
        return $node;
    }


}
