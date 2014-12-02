<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 28.09.2010
 * Time: 15:03:12
 * To change this template use File | Settings | File Templates.
 */
 
class Site_Model_CommentPaginator implements Zend_Paginator_Adapter_Interface{

    private $mapper;
    private $node;
    private $level;
    private $order;

    function __construct($mapper, $node, $level, $order=null) {
        $this->mapper = $mapper;
        $this->node=$node;
        $this->level=$level;
        $this->order=$order;
    }

    public function count() {
        return  $this->mapper->countByNode($this->node,$this->level);
    }

    public function getItems($offset, $itemCountPerPage) {
         return $this->mapper->findByNode($this->node,$this->level,
             $this->order, $offset, $itemCountPerPage);
    }
}
