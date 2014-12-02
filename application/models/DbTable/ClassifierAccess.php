<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zakirov
 * Date: 15.03.2010
 * Time: 18:12:54
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_DbTable_ClassifierAccess extends Zend_Db_Table_Abstract {

    protected $_name = 'taxonomy_classifier_access';
    protected $_primary = array('class_id','role');

    protected $_referenceMap = array(
        'Classifier' => array(
            'columns' => 'class_id',
            'refTableClass' => 'Site_Model_DbTable_Classifier'
        )
    );


    public function selectByRole($role) {
        $select = $this->select()
        ->where("role = ?",$role)
        ->order("class_id");
       //Log::dubug($this,"select",$select);
        return $select;
    }

}
