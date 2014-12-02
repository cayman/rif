<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zakirov
 * Date: 15.03.2010
 * Time: 18:12:54
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_DbTable_Node extends Zend_Db_Table_Abstract {

    protected $_name = 'node';
    protected $_primary = 'id';

    protected $_dependentTables = array('Site_Model_DbTable_NodeTerm', 'Site_Model_DbTable_Comment');
    protected $_referenceMap = array(
        'Type' => array(
            'columns' => 'type_id',
            'refTableClass' => 'Site_Model_DbTable_Type'
        ),
    );

    private function partSelect() {
       $select = $this->select()->setIntegrityCheck(false)->from($this->_name);
       return  $this->unionType($select);
    }

    private function partCount() {
        return $this->select()->from($this->_name, array('COUNT(1) as count'));
    }

    private function unionType($select) {
        $select->join("node_type as NTP", "NTP.id = type_id",
            array('type_code' => 'NTP.code',
                  'type_name' => 'NTP.name',
                  'type_desc' => 'NTP.desc',
                  'text_rows' => 'NTP.text_rows',
                  'node_comment' => 'NTP.comment'
            ));
        return $select;
    }

    private function partJoin($select, $term) {
        if (!empty($term))
            $select->join("node_term as NT_$term", "NT_$term.node_id = node.id", array())
                    ->where("NT_$term.term_id = ?", $term);
        return $select;
    }

      private function partType($select,$type) {
        if (!empty($type)){
            if(is_numeric($type))
                $select->where('type_id = ?', $type);
            else if(is_string($type))
                $select->join("node_type", "node_type.id = type_id", array())
                    ->where("node_type.code = ?", $type);
        }
        return $select;
    }

    private function partAccess($select, $terms_access) {
        if (!empty($terms_access))
            $select->join("node_term as NTA", 'NTA.node_id = node.id', array())
                    ->where("NTA.term_id IN (?)", $terms_access);
        return $select;
    }

    private function partKeys($select, $key) {
        if (isset($key))
            $select->where('node.text like ?', "%{$key}%");
        return $select;
    }


    private function partOrder($select,$terms,$order,$direction) {
        if(empty($direction)) $direction="ASC";
        if(isset($order) && is_string($order) && in_array($order, $this->info(self::COLS)))
           $select->order("$order $direction");
        if(isset($order) && is_numeric($order) && in_array($order, $terms))
           $select->order("CAST(NT_$order.term_value AS DECIMAL) $direction");
        else
           $select->order("date $direction");
        return $select;
    }

    /**
     * Возврашает узел по ID
     * @param  int $id ID узла
     * @param  array  $termsAccess Термины досттупа к материалу*
     */
    public function find($id,$termsAccess=null){
        $select = $this->partSelect()->where('node.id = ?',$id);
        $this->partAccess($select, $termsAccess);
        //Log::debug($this, 'selectById', $select);
        return $this->fetchAll($select);
    }

    /**
     * Возврашает следующий узел по ID заданного
     * @param  int $id ID узла
     * @param  array  $termsAccess Термины досттупа к материалу*
     */
    public function findNext($date,$term,$termsAccess=null){
        $select = $this->partSelect();
        $this->partJoin($select, $term);
        $select->where('node.date < ?',$date);
        $this->partAccess($select, $termsAccess);
        $select->order("date desc")->limit(1);
        //Log::debug($this, 'findNext', $select);
        return $this->fetchAll($select);
    }


    /**
     * Возврашает следующий узел по ID заданного
     * @param  int $id ID узла
     * @param  array  $termsAccess Термины досттупа к материалу*
     */
    public function findPrev($date,$term,$termsAccess=null){
        $select = $this->partSelect();
        $this->partJoin($select, $term);
        $select->where('node.date > ?',$date);
        $this->partAccess($select, $termsAccess);
        $select->order("date asc")->limit(1);
        //Log::debug($this, 'findNext', $select);
        return $this->fetchAll($select);
    }

    /**
     * Возврашает узлы для заднной таксономии (и строки поиска)
     * @param  array $terms Термины таксономии (массив идентификаторов)
     * @param  array  $termsAccess Термины досттупа к материалу
     * @param  string $keys Строка поиска *
     * @param  string $order Поле сортировки
     * @param  string $direction Направление сортировки
     * @return select;
     */
    public function selectByTerms(array $terms, $termsAccess, $keys, $order, $direction) {

        $select = $this->partSelect();
        //$order_set = null;
        foreach ($terms as $term) {
            $this->partJoin($select, $term);
        }
        $this->partAccess($select, $termsAccess);
        $this->partKeys($select, $keys);
        $this->partOrder($select, $terms,  $order, $direction);

        Log::debug($this, 'selectByTerms', $select);
        return $select;
    }

    /**
     * Возврашает количество узлов для заднной таксономии (и строки поиска)
     * @param  array $terms Термины таксономии
     * @param  array  $termsAccess Термины досттупа к материалу
     * @param  string $keys Строка поиска *
     * @return select;
     */
    public function countByTerms(array $terms, $termsAccess, $keys) {
        $select = $this->partCount();
        foreach ($terms as $term)
            $this->partJoin($select, $term);
        $this->partAccess($select, $termsAccess);
        $this->partKeys($select, $keys);
        Log::debug($this, 'countByTerms', $select);
        return $select;
    }

    /**
     * Возврашает последние (по дате) узлы для каждого термина
     * @param  array $terms Термины таксономии
     * @param  array  $termsAccess Термины досттупа к материалу
     * @param  int $count Количестов для каждого термина
     * @return select;
     */
    public function selectLastByTerms(array $terms,$termsAccess, $count) {
        $select_parts = array();
        foreach ($terms as $term) {
            $select = $this->partSelect()
                    ->where('node.hide = ?', 0)
                    ->order("date DESC")->limit($count);
            $this->partJoin($select, $term);
            $this->partAccess($select, $termsAccess);
            //Log::debug($this, $select->__toString());
            $select_parts[] = '(' . $select->__toString() . ')';
        }
        $select = $this->select()->union($select_parts); //->order("date DESC");
        //Log::debug($this, $select->__toString());
        return $select;
    }


    public function countByType($type, $termsAccess) {
        $select = $this->partCount()->where('node.hide = ?', 0);
        $this->partType($select,$type);
        $this->partAccess($select, $termsAccess);
        //Log::debug($this, $select->__toString());
        return $select;
    }


    public function selectByType($type, $termsAccess, $offset, $count = 1) {
        $select = $this->partSelect()->where('node.hide = ?', 0)->limit($count, $offset);
        $this->partType($select,$type);
        $this->partAccess($select, $termsAccess);
        //Log::debug($this, $select->__toString());
        return $select;
    }

    /**
     * Поиск материала идентичного заданному
     * @param array $key слова поиска
     * @param int $node_id заданный материал
     * @return resultSet
     */
    public function selectByKeys(array $keys, $current_node_id = null) {
        $select_parts = array();
        foreach ($keys as $index => $key) {
            Log::debug($this, "$index=>$key");
            $select = $this->select()->setIntegrityCheck(false)
                    ->from($this->_name, array('num' => new Zend_Db_Expr($index), 'node.*'));
            $this->partKeys($select, $key);
            if (isset( $current_node_id)) $select->where('node.id != ?',  $current_node_id);
            $select_parts[] = $select;
        }
        $unionSelect = $this->select()->union($select_parts, Zend_Db_Select::SQL_UNION_ALL);

        $subSelect = $this->select()->setIntegrityCheck(false)
                ->from(array('UN' => $unionSelect), array('count' => 'count(num)', 'UN.*'));
        $subSelect=$this->unionType($subSelect);
        $subSelect->group('id')
                ->having('count > ?', count($keys) - 2)
                ->order('count DESC');
        $subSelect->assemble();

        Log::debug($this, $subSelect->__toString());
        return $subSelect;
    }


     public function insert(array $model){
         $keys=array('type_id','date','title','text','key','desc');
         $data = array_intersect_key($model, array_fill_keys($keys, null));
         return parent::insert($data);
     }

     public function update(array $model,$where){
         $keys=array('id','type_id','date','title','text','key','desc','hide');
         $data = array_intersect_key($model, array_fill_keys($keys, null));
         return parent::update($data,$where);
     }

}

