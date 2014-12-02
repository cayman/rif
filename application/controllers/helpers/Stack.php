<?php
/**
 * Помощник действия для загрузки логина
 *
 * @uses Zend_Controller_Action_Helper_Abstract
 */
class Site_Controller_Helper_Stack extends Zend_Controller_Action_Helper_Abstract
{


    public function init()
    {
    }

    public function add($action, $controller = null, $module = null, array $params = array())
    {
        $currentAction = $this->getActionController();
        if (!$this->hasStackParam()) {
            $currentAction->getHelper('ActionStack')->actionToStack($action, $controller, $module, $params);
        }
        return $this;
    }

    /** Шаблон виджетов для главной страницы
     */
    public function frontTemplate()
    {
        if (!$this->hasStackParam())
            $this->add('statistic', 'block')
                ->add('header', 'block')
                ->add('picture', 'block')
                ->add('login', 'menu')
                ->add('search', 'menu', null, array('stack' => true))
                ->add('twitter', 'block')
                ->add('count', 'block')
                ->add('tags', 'menu')
                ->add('random', 'block')
                ->add('heads', 'menu')
                ->add('user', 'menu');

    }
    /**
     * Шаблон виджетов для страниц  /term/*
     */
    public function termTemplate()
    {
        if (!$this->hasStackParam())
            $this->add('header', 'block')
                ->add('statistic', 'block')
                ->add('breadcrumbs', 'block')
                ->add('login', 'menu')
                ->add('search', 'menu')
                ->add('count', 'block')
                ->add('sort', 'menu')
                ->add('tags', 'menu')
                ->add('heads', 'menu');
    }
    /**
     * Шаблон виджетов для страницы  /search/*
     */
    public function searchTemplate()
    {
        if (!$this->hasStackParam())
            $this->add('header', 'block')
                ->add('statistic', 'block')
                ->add('login', 'menu')
                ->add('search', 'menu')
                ->add('sort', 'menu')
                ->add('tags', 'menu')
                ->add('heads', 'menu');
    }
    /**
     * Базовый набор виджетов
     */
    public function baseTemplate()
    {
        if (!$this->hasStackParam())
            $this->add('header', 'block')
                ->add('statistic', 'block')
                ->add('breadcrumbs', 'block')
                ->add('login', 'menu')
                ->add('search', 'menu')
                ->add('tags', 'menu')
                ->add('heads', 'menu');
    }

    /**
     * Базовый набор виджетов
     */
    public function simpleTemplate()
    {
        if (!$this->hasStackParam())
            $this->add('statistic', 'block')
                ->add('header', 'block')
                ->add('search', 'menu')
                ->add('tags', 'menu')
                ->add('heads', 'menu');
    }


    protected function hasStackParam()
    {
        return $this->getActionController()->getRequest()->getParam('stack') != null;
    }


    /**
     * Паттерн Стратегии: вызываем помощник как метод брокера
     *
     * @param  string $name
     * @param  array|Zend_Config $options
     * @return Zend_Form
     */
    public function direct($action, $controller = null, $module = null, array $params = array())
    {
        $this->add($action, $controller, $module, $params);
    }
}
