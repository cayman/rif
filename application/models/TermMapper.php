<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 27.09.2010
 * Time: 15:23:48
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_TermMapper extends Site_Model_EntityMapper {

    protected $_modelClass = "Site_Model_Term";
    protected $_dbTableClass = "Site_Model_DbTable_Term";
    protected $_dbSubTableClass = "Site_Model_DbTable_TermAccess";


    /**
     * Возвращает один термин, с учетом классифиактора
     * Переопределение базового метода
     * @param  $id
     * @param  array|int|string $classifier Классификаторы*
     * @return Site_Model_Term $node
     */
    public function find($id,$classifier=null) {
        Log::debug($this, "find:$id", $classifier);
        $result = $this->getDbTable()->find($id, $classifier);
        return $this->fetchOne($result);
    }

    /**
     * Возвращает несколько терминов, с учетом классифиактора
     * Переопределение базового метода
     * @param  $id
     * @param  array|int|string $classifier Классификаторы*
     * @return Site_Model_Term $node
     */
    public function findArray(array $id,$classifier=null) {
        Log::debug($this, "find:", $id);
        $select = $this->getDbTable()->selectById($id, $classifier);
        return $this->fetchAll($select);
    }

    /**
     * Возвращает несколько терминов, с учетом классифиактора
     * Переопределение базового метода
     * @param  $id
     * @param  array|int|string $classifier Классификаторы*
     * @return Site_Model_Term $node
     */
//    public function findAsString(array $id,$classifier=null) {
//        Log::debug($this, "find:", $id);
//        $select = $this->getDbTable()->selectById($id, $classifier);
//        return $this->fetchAll($select);
//    }

   /**
     * Возврашает термины заданного классификатора по имени
     * @param  string $name
     * @param  array|int|string $classifier Классификаторы
     * @param  boolen $$calculate C подсчетом количество узлов для термина
     * @return Site_Model_Term[]
     */
    public function findByName($name,$classifier) {
        Log::debug($this, "findByName:", $name);
        $select = $this->getDbTable()->selectByName($name,$classifier);
        return $this->fetchOne($select);
    }
//    public function findByCode($code, $classifier = null) {
//        Log::debug($this, "findByCode:", $code);
//        $select= $this->getDbTable()->selectByCode($code, $classifier);
//        return $this->fetchOne($select);
//    }

    /**
     * Возврашает массив id терминов для которой есть права у роли
     * Если пусто то права на все
     * @param  $role Роль
     * @return int[]
     */
    public function findByRole($role) {
        Log::debug($this,"findByRole:",$role);
        $select = $this->getDbSubTable()->selectByRole($role);
        $result = $this->getDbSubTable()->fetchAll($select);
        $list=array();
        foreach($result as $row){
            $list[$row->term_id] = $row->term_id;
        }
        return $list;
    }

    /**
     * Возврашает термины для заднного классификатора таксономии 
     * @param  array|int|string $classifier Классификаторы
     * @param  boolen $$calculate C подсчетом количество узлов для термина
     * @return Site_Model_Term[]
     */
    public function findByClassifier($classifier,$calculate) {
        Log::debug($this, "findByClassifier:", $classifier);
        $select = $this->getDbTable()->selectByClassifier($classifier,$calculate);
        return $this->fetchAll($select);
    }


}


