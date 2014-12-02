<?php
/**
 * Сервис работы с узлами
 * User: Zakirov
 * Date: 27.09.2010
 * Time: 14:27:06
 */

class Site_Service_Node extends Site_Service_Abstract  {


    /**
     * Return node by ID
     * @throws Site_Service_Exception
     * @param  int $id
     * @return Site_Model_Node
     */
    public function getNode($id) {
        try {
            $node = $this->getNodeMapper()->find($id);
            if (empty($node)) throw new Site_Service_Exception('Material not found',$id,3008,null,'/');
            return $node;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getNode',3009,$e);
        }
    }



    /**
     * Return next node
     * @throws Site_Service_Exception
     * @param Site_Model_Node $node
     * @return Site_Model_Node
     */
    public function getNextNode($node) {
        try {
            $node = $this->getNodeMapper()->findNext($node->date,$node->getMainTerm()->id);
            return $node;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getNextNode',3009,$e);
        }
    }

    /**
     * Return previous node
     * @throws Site_Service_Exception
     * @param Site_Model_Node $node
     * @return Site_Model_Node
     */
    public function getPrevNode($node) {
        try {
            $node = $this->getNodeMapper()->findPrev($node->date,$node->getMainTerm()->id);
            return $node;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getPrevNode',3009,$e);
        }
    }

    /**
     * Append new node
     * @throws Site_Service_Exception
     * @param  Site_Model_Node $node
     * @return int $id
     */
    public function addNode($node) {
        try {
            $id = $this->getNodeMapper()->create($node);
            if (!is_numeric($id)) throw new Site_Service_Exception('Material not saved',null, 3010);
            $node->id=$id;
            $this->saveNodeTerms($node);
            return $id;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'addNode',3011,$e);
        }
    }

    /**
     * Save change in node with node terms
     * @throws Site_Service_Exception
     * @param  Site_Model_Node $node
     * @return null
     */
    public function editNode($node) {
        try {
            $this->getNodeMapper()->modify($node);
            $this->saveNodeTerms($node);
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'editNode',3014,$e);
        }
    }

    /**
     * Save change in node terms
     * @throws Site_Service_Exception
     * @param  Site_Model_Node $node
     * @return int $id
     */
    public function saveNodeTerms($node) {
        try {
            $this->getNodeMapper()->saveTerms($node);
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'saveNodeTerms',3015,$e);
        }
    }


    /**
     * Add new node term value
     * @throws Site_Service_Exception
     * @param int $node
     * @param int $term
     * @param $value
     * @return int
     */
    public function addNodeTermValue($node,$term,$value) {
        try{
           $node_id= ($node instanceof Site_Model_Node )?$node->id:$node;
           $term_id= ($term instanceof Site_Model_Term )?$term->id:$term;
           return $this->getNodeMapper()->addTermValue($node_id,$term_id,$value);
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'addNodeTermValue',3017,$e);
        }

    }


    /**
     * Return nodes by terms and keys
     * @throws Site_Service_Exception
     * @param array $terms
     * @param array $keys
     * @param string $order
     * @param bool $paginate
     * @return Zend_Paginator || Site_Model_Node[]
     */
    public function getNodes($terms, $keys, $order=null, $paginate=true) {
        try {
            //@name Коррекция
            if (empty($terms) && empty($keys))
                throw new Site_Service_Exception('You must specify term or keyword',null,5002);
            //@name Извлечение
            if($paginate){
                $adapter = new Site_Model_NodePagintor($this->getNodeMapper(), $terms, $keys, $order);
                $list = new Zend_Paginator($adapter);
            }else
                $list = $this->getNodeMapper()->findByTerms($terms, $keys,$order);
            return $list;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getNodes',3019,$e);
        }
    }

    /**
     * Return nodes by keys
     * @throws Site_Service_Exception
     * @param array $keys
     * @param string $order
     * @param bool $paginate
     * @return Zend_Paginator || Site_Model_Node[]
     */
    public function getNodesByKeys($keys, $order=null, $paginate=true) {
        try {
            //@name Коррекция
            if (empty($keys))
                throw new Site_Service_Exception('Not specified keyword',null,5004);

            //@name Извлечение
            if($paginate){
                $adapter = new Site_Model_NodePagintor($this->getNodeMapper(),array(), $keys, $order);
                $list = new Zend_Paginator($adapter);
            }else
                $list = $this->getNodeMapper()->findByTerms(array(), $keys,$order);
            return $list;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getNodes',3019,$e);
        }
    }

    /**
     * Return nodes by terms
     * @throws Site_Service_Exception
     * @param array $terms
     * @param string $order
     * @param bool $paginate
     * @return Zend_Paginator || Site_Model_Node[]
     */
    public function getNodesByTerms($terms, $order=null, $paginate=true) {
        try {
            if (empty($terms))
                throw new Site_Service_Exception('You must specify term', null, 5005);
            //@name Извлечение
            if($paginate){
                $adapter = new Site_Model_NodePagintor($this->getNodeMapper(), $terms, null, $order);
                $list = new Zend_Paginator($adapter);
            }else
                $list = $this->getNodeMapper()->findByTerms($terms, null,$order);
            return $list;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getNodes',3019,$e);
        }
    }

    /**
     * Return identity nodes for node
     * @throws Site_Service_Exception
     * @param  Site_Model_Node $node
     * @return Site_Model_Node[]
     */
    public function getNodesIdentity($node) {
        try{
            $word_count = 6;
            $keys = explode(',', str_replace(' ', ',', $node->key));
            $list = $this->getNodeMapper()->findIdentity($this->id, $keys ,$word_count);
            return $list;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getNodesIdentity',3021,$e);
        }
    }


    /**
     * Return count nodes by type
     * @throws Site_Service_Exception
     * @param  id $type
     * @param  boolean $visible
     * @return int
     */
    public function getNodesCount($type,$visible) {
        try {
            return $this->getNodeMapper()->countByType($type,$visible);
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'countNodes',3022,$e);
        }
    }

    /**
     * Возврашает случайны узел для заданного типа - c учетом прав доступа
     * @throws Site_Service_Exception
     * @param  int $type
     * @return Site_Model_Node
     */
    public function getNodeRandom($type) {
        try {
            return $this->getNodeMapper()->findRandom($type);
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getNodeRandom',3023,$e);
        }
    }

    /**
     * Return last nodes by terms
     * @throws Site_Service_Exception
     * @param  array $terms
     * @param  int $count
     * @return Site_Model_Node[]
     */
    public function getNodesLast($terms, $count) {
        try {
            $list = $this->getNodeMapper()->findLastByTerms($terms, $count);
            return $list;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getNodesLast',3024,$e);
        }
    }

    /**
     * Test
     * @param  string $value
     * @return string
     */
    public function about() {
        return 'Node service';
    }



}
