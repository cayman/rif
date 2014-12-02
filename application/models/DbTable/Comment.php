<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zakirov
 * Date: 15.03.2010
 * Time: 18:12:54
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_DbTable_Comment extends Zend_Db_Table_Abstract {

    protected $_name = 'comment';
    protected $_primary = 'id';

    protected $_referenceMap = array(
        'Node' => array(
            'columns' => 'node_id',
            'refTableClass' => 'Site_Model_DbTable_Node'
        ),
        'Level' => array(
            'columns' => 'level',
            'refTableClass' => 'Site_Model_DbTable_CommentLevel'
        ),
    );

    private function partCount() {
        return $this->select()->from($this->_name, array('COUNT(1) as count'));
    }

    private function partRole($select, $role) {
        if(isset($role))
           $select->where("level <= (select max(comment_level.id) from comment_level where role = ? )",$role);
        return $select;
    }

    public function countByIp($ip,$level=null)
    {
        $select = $this->partCount()->where("ip = ?",$ip);
        if(isset($level))
            $select->where("level >= ?",$level);
        Log::debug($this,"count(ip=$ip,level=$level)",$select);
        return $this->fetchRow($select)->count;
    }

     public function countByNode($node=null,$level=null,$role=null) {
        Log::debug($this,"findByNode:",$node);
        $select = $this->partCount();
        $this->partRole($select, $role);
        if(isset($node)) $select->where("node_id = ?",$node);
        if(isset($level)) $select->where("level = ?",$level);
        Log::debug($this,"select",$select);
        return $select;
    }

     public function selectByNode($node=null,$level=null,$order=null,$role=null) {
        if(empty($order)) $order ="date";
        $select = $this->select()->from($this->_name)
                ->setIntegrityCheck(false)
                ->joinLeft("comment_level as CL", "CL.id = level",
                array('level_name' => 'CL.name','level_desc' => 'CL.desc'))
                ->order("$order DESC");
        $this->partRole($select, $role);
        if(isset($node))  $select->where("node_id = ?",$node);
        if(isset($level)) $select->where("level = ?",$level);
        Log::debug($this,"select",$select);
        return $select;
    }

     public function insert(array $model){
         $keys=array('node_id','name','text','date','ip');
         $data = array_intersect_key($model, array_fill_keys($keys, null));
         return parent::insert($data);
     }

     public function update(array $model,$where){
         $keys=array('id','node_id','name','text','level');
         $data = array_intersect_key($model, array_fill_keys($keys, null));
         return parent::update($data,$where);
     }

}
