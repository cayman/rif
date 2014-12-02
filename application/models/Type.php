<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 29.09.2010
 * Time: 17:52:32
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_Type extends Site_Model_Entity {

    protected $_name;
    protected $_desc;
    protected $_code; //Заголовок
    protected $_text_rows;
    protected $_comment;

    protected $_classifier=array();
    protected $_classifier_id=array();


    /**
    * Добавление настроки классификатора для узла
     * @param  string $class
     * @param  array|Zend_Db_Table_Row_Abstract $class
     * @return Site_Model_Type
     */
    public function addClassifier($code, $class) {
        if ($class instanceof Zend_Db_Table_Row_Abstract) {
            $class = new Site_Model_Classifier($class);
            $class_id = $class->id;
        } else if ($class instanceof Site_Model_Classifier) {
            $class_id = $class->id;
        } else if (is_array($class) && isset($class[ID])) {
            $class_id = $class[ID];
        }
        if (isset($class_id)) $this->_classifier_id[$class_id] = $class_id;

        $this->_classifier[$code] = $class;
        return $this;
    }



}
