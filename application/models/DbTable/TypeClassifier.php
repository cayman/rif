<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zakirov
 * Date: 15.03.2010
 * Time: 18:12:54
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_DbTable_TypeClassifier extends Zend_Db_Table_Abstract {

    protected $_name = 'node_type_classifier';
    protected $_primary = array('type_id', 'class_id');

    protected $_referenceMap = array(
        'Type' => array(
            'columns' => 'type_id',
            'refTableClass' => 'Site_Model_DbTable_Type'
        ),
        'Classifier' => array(
            'columns' => 'class_id',
            'refTableClass' => 'Site_Model_DbTable_Classifier'
        ),
    );



}
