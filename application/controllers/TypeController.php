<?php
/**
 * User: Zakirov
 * Date: 15.03.2010
 * Time: 17:05:20
 * To change this template use File | Settings | File Templates.
 */

class TypeController  extends Zend_Controller_Action {
    private $cache_core;
    private $types;

    public function init() {
        $this->cache_core = $this->getInvokeArg('cache_core');
        $this->types = Site_Service_Registry::get(TYPE);
    }

    /**
     * Описание
     * @return void
     */
    public function openAction() {
        //@name Widgets
        $this->_helper->Stack->baseTemplate();
        Log::info($this, _OPEN, $this->_getAllParams());
        $id = (int) $this->_getParam(ID);
        //@name get Type
        $type = $this->types->getType($id);
        $this->view->type = $type;
        //@name setHeader
        $this->view->headline = $this->view->translate("type.info");
        $this->view->headTitle($this->view->headline);

    }

    /**
     * Список типов
     * @return void
     */
    public function listAction() {
        //@name Widgets
        $this->_helper->Stack->baseTemplate();
        Log::info($this, _LIST, $this->_getAllParams());
        //@name get Data
        $this->view->help = $this->view->translate('Type use for');
        $this->view->list = $this->types->getTypes();
        //@name setHeader
        $this->view->headline = $this->view->translate("type.list");
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
        $type =  new Site_Model_Type();
        $action = $this->_helper->Url->url(array(CONTROLLER => TYPE, ACTION => _ADD), TYPE, true);
        $form = new Site_Form_Type($action);

        if (!$this->getRequest()->isPost()) { //отображение формы
            Log::debug($this, "addAction - is not Post - show form");
            //$form->populate($type);
            $form->setDefault(PAGE, $_SERVER[HTTP_REFERER]);
        } else {
            Log::debug($this, "addAction - is Post"); //Post запрос
            if ($form->isValid($_POST)) {
                $form->copy($type);
                $this->types->addType($type);
                $this->_helper->Messenger('Type was created');
                $this->_redirect($form->getValue(PAGE));
                $this->cache_core->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array(CLASSIFIER));
                Log::info($this, "OK");
            }
        }
        //@name setHeaders
        $this->view->form = $form;
        $this->view->headline = $this->view->translate('type.add');
        $this->view->headTitle($this->view->headline);

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
        $id = (int) $this->_getParam(ID);
        //@name get Type
         $type = $this->types->getType($id);

        //@name getData
        $action = $this->_helper->Url->url(array(CONTROLLER => TYPE, ACTION => _ADD), TYPE, true);
        $form = new Site_Form_Type($action);

       //@process form
        Log::debug($this, "copyAction - show form");
        $type->id=null;
        $form->populate($type);
        $form->setPage($_SERVER[HTTP_REFERER]);

        //@name setHeaders
        $this->view->form = $form;
        $this->view->headline = $this->view->translate('type.add');
        $this->view->headTitle($this->view->headline);

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
        $id = (int) $this->_getParam(ID);
        //@name get Type
        $type = $this->types->getType($id);

        //@name getData
        $action = $this->_helper->Url->url(array(CONTROLLER => TYPE, ACTION => _EDIT, ID => $id), TYPE, true);
        $form = new Site_Form_Type($action);

        if (!$this->getRequest()->isPost()) { //отображение формы
            Log::debug($this, "editAction - is not Post - show form");
            $form ->populate($type);
            $form->setDefault(PAGE, $_SERVER[HTTP_REFERER]);
        } else {
            Log::debug($this, "editAction - is Post"); //Post запрос
            if ($form->isValid($_POST)) {
                $form->copy($type);
                $this->taxomomy->editType($type);
                $this->_helper->Messenger('Changes made');
                $this->_redirect($form->getValue(PAGE));
                $this->cache_core->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array(CLASSIFIER));                   
                Log::info($this, "OK");
            }
        }
        //@name setHeaders
        $this->view->form = $form;
        $this->view->headline = $this->view->translate('type.edit');
        $this->view->headTitle($this->view->headline);

        $this->render(FORM, 'sub');
    }

}
