<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 29.09.2010
 * Time: 18:26:35
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_LevelMapper extends Site_Model_EntityMapper  {

    protected $_modelClass = "Site_Model_Level";
    protected $_dbTableClass = "Site_Model_DbTable_CommentLevel";


    /**
     * Возврашает все уровни, с учетом роли
     * @return Site_Model_Level[]   @todo
     */
//    public function findAll() {
//        $select = $this->getDbSubTable()->selectByRole($role);
//        return $this->fetchAll($select);
//    }

}
