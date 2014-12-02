<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zakirov
 * Date: 15.03.2010
 * Time: 18:12:54
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_DbTable_TermAccess extends Zend_Db_Table_Abstract {

    protected $_name = 'taxonomy_term_access';
    protected $_primary = array('term_id','role');

    public function selectByRole($role) {
        $select = $this->select()
        ->where("role = ?",$role)
        ->order("term_id");
        //Log::dubug($this,"select",$select);
        return $select;
    }
}
