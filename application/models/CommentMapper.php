<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 27.09.2010
 * Time: 15:23:48
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_CommentMapper extends Site_Model_EntityMapper {

    protected $_access;
    protected $_modelClass = "Site_Model_Comment";
    protected $_dbTableClass = "Site_Model_DbTable_Comment";
    protected $_dbSubTableClass = "Site_Model_DbTable_CommentLevel";


    protected function init(){
        $this->_access = $this->_registry->service(ACCESS);
    }


    /**
     * Возврашает количество комментариев для заднного узла - c учетом прав доступа
     * @param  int $node Узел
     * @param  int $level Для показа только одного уровня
     * @return int;
     */
    public function countByNode($node=null,$level=null) {
        $role = $this->_access->getRole();
        $select = $this->getDbTable()->countByNode($node, $level, $role);
        return $this->fetchCount($select);
    }

   /**
     * Возврашает количество комментариев для заднного ip
     * @param  string $node Узел
     * @param  int $level Для показа только одного уровня
     * @return int;
     */
    public function countByIp($ip,$level=null) {
        $select = $this->getDbTable()->countByIp($ip, $level);
        return $this->fetchCount($select);
    }
    /**
     * Возврашает комментарии для заднного узла - c учетом прав доступа
     * @param  int $node Узел
     * @param  int $level Для показа только одного уровня
     * @param  string $order Поле сортировки
     * @param  string $role Роль
     * @param  int $offset Смещение в результатах запроса
     * @param  int $itemCount Макс количество возвращаемых узлов
     * @return Site_Model_Comment[]
     */
    public function findByNode($node=null, $level=null, $order = null,$offset = 0, $itemCount = null) {
        Log::debug($this, "findByNode:", $node);
        $role = $this->_access->getRole();
        $select = $this->getDbTable()->selectByNode($node, $level, $order, $role);
        if (isset($itemCount)) $select->limit($itemCount, $offset);
        return $this->fetchAll($select);
    }

    /**
     * Возвращает список уровней коментариев
     */
    public function findLevels() {
        $role = $this->_access->getRole();
        $select = $this->getDbSubTable()->select()
        ->where("role = ?",$role)
        ->order("ID");
        $row = $this->getDbTable()->fetchRow($select);
        return $row;
    }


}
