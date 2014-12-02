<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zakirov
 * Date: 15.03.2010
 * Time: 18:12:54
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_DbTable_CommentLevel extends Zend_Db_Table_Abstract {

    protected $_name = 'comment_level';
    protected $_primary = 'id';

    protected $_dependentTables = array('Site_Model_DbTable_Comment');

}
