<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 27.09.2010
 * Time: 14:27:06
 * To change this template use File | Settings | File Templates.
 */

class Site_Service_Taxonomy extends Site_Service_Abstract
{

    protected $_classifiers;


    /**
     * Return classifier by ID
     * @throws Site_Service_Exception
     * @param  int $id
     * @return Site_Model_Classifier
     */
    public function getClassifier($id)
    {
        try {
            if (empty($id))
                throw new Site_Service_Exception('You must specify classifier', $id, 4018);
            if (is_numeric($id))
                $classifier = $this->getClassifierMapper()->find($id);
            else
                $classifier = $this->getClassifierMapper()->findByCode($id);

            if (empty($classifier)) throw new Site_Service_Exception('Classifier not found', $id, 4001);
            return $classifier;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error", 'getClassifier', 4002, $e);
        }
    }

    /**
     * Return all classifiers
     * @throws Site_Service_Exception
     * @return Site_Model_Classifier[]
     */
    public function getClassifiers()
    {
        try {
            if ($this->_classifiers === null) {
                $cache = Zend_Registry::get(CACHE_CORE);
                if (!$this->_classifiers = $cache->load(CLASSIFIER)) {
                    $this->_classifiers = $this->getClassifierMapper()->findAll();
                    $cache->save($this->_classifiers, CLASSIFIER, array(CLASSIFIER));
                }
            }
            return $this->_classifiers;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception('Method error', 'getClassifiers', 4003, $e);
        }
    }

    /**
     * Return all classifiers as array
     * @throws Site_Service_Exception
     * @return String[]
     */
    public function getClassifiersOptions()
    {
        $classOptions = array();
        foreach ($this->getClassifiers() as $classifier) {
            $classOptions[$classifier->id] = $classifier->name;
        }
        return $classOptions;
    }

    /**
     * Append new Classifier
     * @throws Site_Service_Exception
     * @param Site_Model_Classifier $classifier
     * @return int $id
     */
    public function addClassifier($classifier)
    {
        try {
            $id = $this->getClassifierMapper()->create($classifier);
            if (!is_numeric($id)) throw new Site_Service_Exception('Classifier not saved', null, 4004);
            return $id;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error", 'addClassifier', 4005, $e);
        }
    }

    /**
     * Save change in Classifier
     * @throws Site_Service_Exception
     * @param  Site_Model_Classifier $classifier
     * @return null
     */
    public function editClassifier($classifier)
    {
        try {
            if (!$this->getClassifierMapper()->modify($classifier))
                throw new Site_Service_Exception('Classifier not saved', null, 4006);
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error", 'editClassifier', 4007, $e);
        }
    }


    /**
     * Return term by ID
     * @throws Site_Service_Exception
     * @param  int $id
     * @param  int $classifier
     * @return Site_Model_Term
     */
    public function getTerm($id, $classifier = null)
    {
        try {
            if (empty($id))
                throw new Site_Service_Exception('You must specify term', $id, 4017);
            if (is_numeric($id))
                $term = $this->getTermMapper()->find($id, $classifier);
            else
                $term = $this->getTermMapper()->findByName($id, $classifier);


            if (empty($term)) throw new Site_Service_Exception('Term not found', $id, 4008, null, '/');
            return $term;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error", "getTerm($id)", 4009, $e);
        }
    }

    /**
     * Return terms by ID
     * @throws Site_Service_Exception
     * @param int[] $id
     * @param int $classifier
     * @return Site_Model_Term[]
     */
    public function getTermsArray(array $id, $classifier = null)
    {
        try {
            $list = $this->getTermMapper()->findArray($id, $classifier);
            return $list;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error", 'getTermArray', 4010, $e);
        }
    }


    /**
     * Return terms by classifier
     * @throws Site_Service_Exception
     * @param  int $classifier
     * @param  boolean $calculate
     * @return Site_Model_Term[]
     */
    public function getTerms($classifier, $calculate = false)
    {
        try {
            if ($classifier instanceof Site_Model_Classifier)
                $classifier = $classifier->id;
            $list = $this->getTermMapper()->findByClassifier($classifier, $calculate);
            return $list;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error", 'getTerms', 4011, $e);
        }
    }

    /**
     *  Return terms values type
     * @return String[]
     */
    public function getTermValuesOptions()
    {
        return array(0 => 'Не используется',
            Site_Model_Term::DECIMAL_VALUE => 'Целочисленное',
            Site_Model_Term::CHAR_VALUE => 'Строковое',
        );

    }

//    static public function getOptions() {
//        $typeOptions = array();
//        foreach (self::getAll() as $type) {
//            $typeOptions[$type->id] = $type->name;
//        }
//        return $typeOptions;
//    }

//    public function findAllById($id, $classifier = null) {
//        Log::debug($this, "findAllById:(id?$id)", $classifier);
//        $select = $this->getDbTable()->selectById($id, $classifier);
//        $resultSet = $this->getDbTable()->fetchAll($select);;
//        return $resultSet;
//    }


    /**
     * Append new Term
     * @throws Site_Service_Exception
     * @param Site_Model_Term $term
     * @return int $id
     */
    public function addTerm($term)
    {
        try {
            $id = $this->getTermMapper()->create($term);
            if (!is_numeric($id)) throw new Site_Service_Exception('Term not saved', null, 4012, 8);
            return $id;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error", 'addTerm', 4014, $e);
        }
    }

    /**
     * Save change in Term
     * @throws Site_Service_Exception
     * @param Site_Model_Term $term
     * @return void
     */
    public function editTerm($term)
    {
        try {
            if (!$this->getTermMapper()->modify($term))
                throw new Site_Service_Exception('Term not saved', null, 4015);
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error", 'editTerm', 4016, $e);
        }
    }


    /**
     * @return string
     */
    public function about()
    {
        return 'Taxonomy service';
    }


}
