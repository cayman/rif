<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zakirov
 * Date: 15.03.2010
 * Time: 18:12:54
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_DbTable_NodeTerm extends Zend_Db_Table_Abstract {

    protected $_name = 'node_term';
    protected $_primary = array('node_id', 'term_id');

    protected $_referenceMap = array(
        'Node' => array(
            'columns' => 'node_id',
            'refTableClass' => 'Site_Model_DbTable_Node'
        ),
        'Term' => array(
            'columns' => 'term_id',
            'refTableClass' => 'Site_Model_DbTable_Term'
        ),
    );

    /**
     * Возвращает все термины для узла или узлов
     * @param  int|array $node
     * @param  $role Роль пользователя
     * @return Zend_Db_Select
     */
    public function selectByNode($node,$classifier_access) {
        $select = $this->select()->setIntegrityCheck(false)->from($this->_name,
            array('node_id','term_value'))
        ->join("taxonomy_term as TT", "TT.id = term_id",
            array('TT.id','TT.class_id',
                  'TT.date','TT.desc','TT.name','TT.title','TT.pic','TT.order'))
        ->join("taxonomy_classifier as TC", "TC.id = TT.class_id",
            array('class_code'=>'TC.code'))
        ->where('node_id in (?)', $node)
        ->order(array ('node_id','TC.order'));
        if(!empty($classifier_access))
           $select ->where('TT.class_id in (?)', $classifier_access);
        Log::info($this,"select",$select);
        return $select;
    }
//    public function deleteByNode($node,$classifier_access) {
//         $classifierAccess = $this->_access->getLegalClassifier();
//         $where=array();
//         $where = $this->getAdapter()->quoteInto('node_id = ?', 1235);
//         $where = $this->getAdapter()->quoteInto('node_id = ?', 1235);
//         $this->delete("node_id = {$id} and tetm_id in (?)");
//    }


}
