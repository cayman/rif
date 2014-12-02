<?php
/**
 * Асбтрактный класс для всех сервисов
 * Реализует шаблон Facade (дочерние классы)
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 01.10.2010
 * Time: 11:05:27
 * To change this template use File | Settings | File Templates.
 */

abstract class Site_Service_Abstract {

    protected $_registry;

    function __construct($registry) {
        $this->_registry=$registry;
        $this->init();
    }

    protected function init(){

    }

    protected function getClassifierMapper() {
        return $this->_registry->mapper('Classifier');
    }

    protected function getCommentMapper() {
        return $this->_registry->mapper('Comment');
    }

    protected function getNodeMapper() {
        return $this->_registry->mapper('Node');
    }

    protected function getTermMapper() {
        return $this->_registry->mapper('Term');
    }

    protected function getTypeMapper() {
        return $this->_registry->mapper('Type');
    }

    protected function getLevelMapper() {
        return $this->_registry->mapper('Level');
    }

    protected function getUserMapper() {
        return $this->_registry->mapper('User');
    }

    /**
     * Service info
     * @abstract
     */
    abstract public function about();
}
