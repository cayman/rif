<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 28.09.2010
 * Time: 15:03:12
 * To change this template use File | Settings | File Templates.
 */
 
class Site_Model_NodePagintor implements Zend_Paginator_Adapter_Interface{

    private $nodeMapper;
    private $terms;
    private $keys;
    private $order;
    private $direction;

    function __construct($mapper, $terms, $keys, $order=null, $direction='DESC') {
        $this->nodeMapper = $mapper;
        $this->terms=$terms;
        $this->keys=$keys;
        $this->order=$order;
        $this->direction=$direction;
    }

    public function count() {
        return  $this->nodeMapper->countByTerms($this->terms,$this->keys);
    }

    public function getItems($offset, $itemCountPerPage) {
         return $this->nodeMapper->findByTerms($this->terms,$this->keys,
             $this->order, $this->direction, $offset, $itemCountPerPage);
    }
}
