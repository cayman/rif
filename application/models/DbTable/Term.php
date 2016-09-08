<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zakirov
 * Date: 15.03.2010
 * Time: 18:12:54
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_DbTable_Term extends Zend_Db_Table_Abstract {

    protected $_name = 'taxonomy_term';
    protected $_primary = 'id';

    protected $_dependentTables = array('Site_Model_DbTable_NodeTerm');

    protected $_referenceMap = array(
        'Classifier' => array(
            'columns' => 'class_id',
            'refTableClass' => 'Site_Model_DbTable_Classifier',
            'refColumns' => 'id'
        ),
    );

    private function partSelect() {
        return $this->select()->setIntegrityCheck(false)->from("taxonomy_term as TT")
                ->join("taxonomy_classifier as TC", "TC.id = TT.class_id",
            array('code' => 'TC.code'));
    }


    private function partClassifier($select,$classifier) {
        if (isset($classifier))
          if(is_numeric($classifier))
            $select->where('TT.class_id = ?', $classifier);
          else if(is_string($classifier))
            $select->where('TC.code = ?', $classifier);
          else if(is_array($classifier))
             $select->where('TT.class_id in (?)', $classifier);
        return $select;
    }
    /**
     * Возврашает термин по ID
     * @param  int $id ID узла
     * @param  int|string|null $classifier ID или Код классификатора
     * return  Zend_Db_Select
     */
    public function findById($id,$classifier=null){
        $select = $this->partSelect()->where('TT.id = ?',$id);
        $this->partClassifier($select,$classifier);
        Log::debug($this, 'selectById', $select);
        return $this->fetchAll($select);
    }

    /**
     * Возврашает термины по ID
     * @param  int[] $id ID узла
     * @param  int|string|null $classifier ID или Код классификатора
     * return  Zend_Db_Select
     */
    public function selectById(array $id,$classifier=null){
        $select = $this->partSelect()->where('TT.id in (?)',$id);
        $this->partClassifier($select,$classifier);
        //Log::dubug($this, 'selectById', $select);
        return  $select;
    }

    /**
     * Возврашает термин по имени
     * @param  int $id ID узла
     * @param  int|string|null $classifier ID или Код классификатора
     * return  Zend_Db_Select
     */
    public function selectByName($name, $classifier) {
        $select = $this->partSelect()->where('TT.name = ?', $name);
        $this->partClassifier($select,$classifier);
        //Log::dubug($this, 'selectByName', $select);
        return $select;
    }
//    public function selectByCode($code, $classifier) {
//        $select = $this->partSelect($select)->where('TT.code = ?', $code);
//        $this->partClassifier($select,$classifier);
//        //Log::dubug($this, 'selectByCode', $select);
//        return $select;
//    }
    /**
     * Возврашает термины для заднного классификатора таксономии
     * @param  array|int|string $classifier Классификаторы
     * @param  boolen $$calculate C подсчетом количество узлов для термина
     */
    public function selectByClassifier($classifier,$calculate=false) {
        $select = $this->partSelect()
            ->order(array('TC.order','TT.order'));
        $this->partClassifier($select,$classifier);

        if($calculate)
            $select->setIntegrityCheck(false)
                ->joinLeft('node_term as NT', 'NT.term_id=TT.id',
                array('count' => 'COUNT(NT.node_id)'))
                ->group('TT.id');

        //Log::dubug($this, 'select', $select);
        return $select;
    }



    /**---------------------------OLD--------------------------*/

//
//    public function findByName($name, $classifier = null) {
//        $select = $this->select()->where('name = ?', $name);
//        if (isset($classifier))
//            $select->where('class_id = ?', $classifier);
//        Log::info($this, 'findByName', $select);
//        return $this->fetchRow($select);
//    }
//   public function selectById($id, $classifier = null) {
//        $select = $this->select()->where('id in (?)', $id);
//        if (isset($classifier)){
//            $select->where('class_id = ?', $classifier);
//        }
//        Log::info($this, 'findById', $select);
//        return $select;
//    }
//
//    public function findByClassifier($classifier) {
//        $select = $this->select()->setIntegrityCheck(false)
//        ->from($this->_name)
//        ->joinLeft('node_term', 'node_term.term_id=taxonomy_term.id',
//            array('count' => 'COUNT(node_term.node_id)'))
//        ->join("taxonomy_classifier", "taxonomy_classifier.id = taxonomy_term.class_id",array())
//        ->group('taxonomy_term.id')
//        ->order(array('taxonomy_classifier.order','taxonomy_term.order'));
//
//        if (isset($classifier))
//           $select->where('taxonomy_term.class_id in (?)', $classifier);
//
//        Log::info($this, 'findByClassifier', $select);
//        return $this->fetchAll($select);
//    }


     public function insert(array $model){
         $keys=array('class_id','date','name','desc','order');
         $data = array_intersect_key($model, array_fill_keys($keys, null));
         return parent::insert($data);
     }

     public function update(array $model,$where){
         $keys=array('id','class_id','date','name','desc','order');
         $data = array_intersect_key($model, array_fill_keys($keys, null));
         return parent::update($data,$where);
     }
}
