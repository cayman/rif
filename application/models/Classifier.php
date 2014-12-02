<?php
/**
 * A Domain Model class.
 * User: Zakirov
 * Date: 27.09.2010
 * Time: 14:32:20
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_Classifier extends Site_Model_Entity {

    protected $_code;
    protected $_name; //Заголовок
    protected $_desc; //Описание
    protected $_order; //Порядок
    protected $_autocomplete; //
    protected $_multiple; //
    protected $_value_name;
    protected $_value_type;
    protected $_term_required;
    protected $_term_default;
}
