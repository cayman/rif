<?php
/**
 * User: Zakirov
 * Date: 15.03.2010
 * Time: 17:05:20
 * To change this template use File | Settings | File Templates.
 */

class ClassifierController  extends Zend_Controller_Action {
    private $cache_core;
    private $taxonomy;

    public function init() {
        $this->cache_core = $this->getInvokeArg('cache_core');
        $this->taxonomy = Site_Service_Registry::get(TAXONOMY);
    }

    /**
     * Описание
     * @return void
     */
    public function openAction() {
        $this->_helper->Stack->baseTemplate();
        Log::info($this, _OPEN, $this->_getAllParams());

        //@name getParams
        $id = $this->_getParam(ID);
        if ($id == null) $this->_forward('list');
        //@name get Classifier
        $classifier = $this->taxonomy->getClassifier($id);
        $this->view->classifier = $classifier;

        if (!$this->_helper->Cache(CLASSIFIER.$id, TERMS)) {
            $terms = $this->taxonomy->getTerms($classifier);
            $this->view->terms = $terms;
            if($classifier->code == HEAD || $classifier->code == TAG )
                $this->view->action=$classifier->code;
            else
                $this->view->action=_OPEN;
        }
        $this->view->headline = $classifier->name;
        $this->view->headTitle($this->view->headline);

    }
    /**
     * Список классификаторов
     * @return void
     */
    public function listAction() {
        //@name Widgets
        $this->_helper->Stack->baseTemplate();
        Log::info($this, _OPEN, $this->_getAllParams());
        //@name getData
        $this->view->help = $this->view->translate('Classifier use for');
        $this->view->list = $this->taxonomy->getClassifiers();
        //@name setHeaders
        $this->view->headline = $this->view->translate(CLASSIFIER);
        $this->view->headTitle($this->view->headline);

    }

     /**
     * Создание нового
     * @return void
     */
    public function addAction() {
        //@name Widgets
        if ($this->_request->isXmlHttpRequest())
            $this->_helper->layout->setLayout('ajax');
        else
            $this->_helper->Stack->baseTemplate();
        Log::info($this, _ADD, $this->_getAllParams());

        //@name getParams
        $classifier = new Site_Model_Classifier();

        $action = $this->_helper->Url->url(array(CONTROLLER => CLASSIFIER, ACTION => _ADD), CLASSIFIER, true);
        $form = new Site_Form_Classifier($action);

        if (!$this->getRequest()->isPost()) { //отображение формы
            Log::debug($this, "addAction - is not Post - show form");
            //$form->populate($classifier);
            $form->setDefault(PAGE, $_SERVER[HTTP_REFERER]);
        } else {
            Log::debug($this, "addAction - is Post"); //Post запрос
            if ($form->isValid($_POST)) {
                $form->copy($classifier);
                $this->taxonomy->addClassifier($classifier);
                $this->_helper->Messenger("Classifier created");
                $this->_redirect($form->getValue(PAGE));
                $this->cache_core->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array(CLASSIFIER));
                Log::info($this, "OK");
            }
        }
        //@name setHeaders
        $this->view->form = $form;
        $this->view->headline = $this->view->translate('classifier.add');
        $this->view->headTitle($this->view->headline);
        //@name Widgets

        $this->render(FORM, 'sub');
    }

    /**
     * Создание  нового из существующего
     * @return void
     */
    public function copyAction() {
        //@name Widgets
        if ($this->_request->isXmlHttpRequest())
            $this->_helper->layout->setLayout('ajax');
        else
            $this->_helper->Stack->baseTemplate();
        Log::info($this, _ADD, $this->_getAllParams());

        //@name getParams
        $id = $this->_getParam(ID,1);

        //@name getTerm
        $classifier = $this->taxonomy->getClassifier($id);
        //@name getData
        $action = $this->_helper->Url->url(array(CONTROLLER => CLASSIFIER, ACTION => _ADD), CLASSIFIER, true);
        $form = new Site_Form_Classifier($action);

       //@process form
        Log::debug($this, "copyAction - show form");
        $date = new Zend_Date();
        $classifier->id=null;
        $form->populate($classifier);
        $form->setPage($_SERVER[HTTP_REFERER]);

        //@name setHeaders
        $this->view->form = $form;
        $this->view->headline = $this->view->translate('classifier.add');
        $this->view->headTitle($this->view->headline);

        //@name Widgets
        $this->_helper->Navigation->setActive(CLASSIFIER.$id);

        $this->render(FORM, 'sub');
    }

    /**
     * Редактирование
     * @return void
     */
    public function editAction() {
        //@name Widgets
        if ($this->_request->isXmlHttpRequest())
            $this->_helper->layout->setLayout('ajax');
        else
            $this->_helper->Stack->baseTemplate();

        Log::info($this, _EDIT, $this->_getAllParams());
        //@name getParams
        $id = $this->_getParam(ID);
        //@name get Classifier
        $classifier = $this->taxonomy->getClassifier($id);

        //@name getData
        $action = $this->_helper->Url->url(array(CONTROLLER => CLASSIFIER, ACTION => _EDIT, ID => $id), CLASSIFIER, true);
        $form = new Site_Form_Classifier($action);

        if (!$this->getRequest()->isPost()) { //отображение формы
            Log::debug($this, "editAction - is not Post - show form");
            $form->populate($classifier);
            $form->setDefault(PAGE, $_SERVER[HTTP_REFERER]);
        } else {
            Log::debug($this, "editAction - is Post"); //Post запрос
            if ($form->isValid($_POST)) {
                $form->copy($classifier);
                $this->taxonomy->editClassifier($classifier);
                $this->_helper->Messenger('Changes made');
                $this->cache_core->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array(CLASSIFIER));
                $this->_redirect($form->getValue(PAGE));
                Log::info($this, "OK");
            }
        }
        //@name setHeaders
        $this->view->form = $form;
        $this->view->headline = $this->view->translate('classifier.edit');
        $this->view->headTitle($this->view->headline);
        //@name Widgets
        $this->_helper->Navigation->setActive(CLASSIFIER.$classifier->id);

        $this->render(FORM, 'sub');
    }



}
