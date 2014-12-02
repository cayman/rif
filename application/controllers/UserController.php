<?php
/**
 * User: Zakirov
 * Date: 15.03.2010
 * Time: 17:05:20
 * To change this template use File | Settings | File Templates.
 */

class UserController  extends Zend_Controller_Action {
    private $users;

    public function init() {
       $this->users = Site_Service_Registry::get(USER);
    }

    /**
     * Список пользователей
     * @return void
     */
    public function listAction() {
        //@name Widgets
        $this->_helper->Stack->baseTemplate();
        Log::info($this, _LIST, $this->_getAllParams());
        //@name getData
        $this->view->help = $this->view->translate('Users use for');
        $this->view->list =  $this->users->getUsers();
        //@name setHeaders
        $this->view->headline = $this->view->translate("users");
        $this->view->headTitle($this->view->headline);

    }

    /**
     * Описание
     * @return void
     */
    public function openAction() {
        //@name Widgets
        $this->_helper->Stack->baseTemplate();
        Log::info($this, _OPEN, $this->_getAllParams());
        //@name getParams
        $id = $this->_getParam(ID);
        if ($id == null) $this->_forward('list');
        //@name get User
        $user = $this->users->getUser($id);
        $this->view->user = $user;
        //@name setHeaders
        $this->view->headline = $this->view->translate("user.info");
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
        $user = new Site_Model_User();

        $action = $this->_helper->Url->url(array(CONTROLLER => USER, ACTION => _ADD), USER, true);
        $form = new Site_Form_User($action);

        if (!$this->getRequest()->isPost()) { //отображение формы
            Log::debug($this, "addAction - is not Post - show form");
            //$form->populate($user);
            $form->setDefault(PAGE, $_SERVER[HTTP_REFERER]);
        } else {
            Log::debug($this, "addAction - is Post"); //Post запрос
            if ($form->isValid($_POST)) {
                $form->copy($user);
                $this->users->addUser($user);
                $this->_helper->Messenger('User was created');
                $this->_redirect($form->getValue(PAGE));
                Log::info($this, "OK");
            }
        }
        //@name setHeaders
        $this->view->form = $form;
        $this->view->headline = $this->view->translate('user.add');
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
        $id = $this->_getParam(ID,1);

        //@name get User
        $user = $this->users->getUser($id);

        //@name getData
        $action = $this->_helper->Url->url(array(CONTROLLER => USER, ACTION => _ADD), USER, true);
        $form = new Site_Form_User($action);

       //@process form
        Log::debug($this, "copyAction - show form");
        $user->id=null;
        $form->populate($user);
        $form->setPage($_SERVER[HTTP_REFERER]);

        //@name setHeaders
        $this->view->form = $form;
        $this->view->headline = $this->view->translate('user.add');
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
        $id =  $this->_getParam(ID);
        //@name get User
        $user = $this->users->getUser($id);

        //@name getData
        $action = $this->_helper->Url->url(array(CONTROLLER =>USER, ACTION => _EDIT, ID => $id), USER, true);
        $form = new Site_Form_User($action);

        if (!$this->getRequest()->isPost()) { //отображение формы
            Log::debug($this, "editAction - is not Post - show form");
            $form ->populate($user);
            $form->setDefault(PAGE, $_SERVER[HTTP_REFERER]);
        } else {
            Log::debug($this, "editAction - is Post"); //Post запрос
            if ($form->isValid($_POST)) {
                $form->copy($user);
                $this->users->editUser($user);
                $this->_helper->Messenger('Changes made');
                $this->_redirect($form->getValue(PAGE));
                Log::info($this, "OK");
            }
        }
        //@name setHeaders
        $this->view->form = $form;
        $this->view->headline = $this->view->translate('user.edit');
        $this->view->headTitle($this->view->headline);

        //@name Widgets
        $this->render(FORM, 'sub');
    }

}
