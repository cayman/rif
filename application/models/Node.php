<?php
/**
 * A Domain Model class.
 * User: Zakirov
 * Date: 27.09.2010
 * Time: 14:32:20
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_Node extends Site_Model_Entity {

    protected $_date;
    protected $_title; //Заголовок
    protected $_text; //Основной текст
    protected $_pic; //Рисунок
    protected $_key; // Ключи
    protected $_desc; //Описание
    protected $_hide;

    protected $_type_id;
    protected $_type_name;
    protected $_type_desc;
    protected $_type_code; //Заголовок
    protected $_text_rows;
    protected $_node_comment;


    protected $_terms = array();
    protected $_terms_id = array();

    /**
     * Добавление термина для узла
     * @param  string $class
     * @param  array|Zend_Db_Table_Row_Abstract $term
     * @return Site_Model_Node
     */
    public function addTerm($class, $term) {
        if (!isset($this->_terms[$class]))
            $this->_terms[$class] = array();

        if ($term instanceof Zend_Db_Table_Row_Abstract) {
            $term = new Site_Model_Term($term);
            $term_id = $term->id;
        } else if ($term instanceof Site_Model_Term) {
            $term_id = $term->id;
        } else if (is_array($term) && isset($term[ID])) {
            $term_id = $term[ID];
        }

        if (isset($term_id)) $this->_terms_id[$term_id] = $term_id;
        $this->_terms[$class][] = $term;

        return $this;
    }

    public function getTerm($class){
        if(isset($this->_terms[$class]))
            return $this->_terms[$class];
        else null;
    }

    public function getMainTerm(){
        if(isset($this->_terms[HEAD]))
           return $this->_terms[HEAD][0];
        else null;
    }


}
