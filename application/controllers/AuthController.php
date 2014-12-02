<?php

class AuthController extends Zend_Controller_Action {
    private $auth;

    public function init() {
        $this->_helper->Layout->setLayout(AUTH);
        $this->auth = Site_Service_Registry::get(ACCESS);
    }

    public function loginAction() {
        Log::info($this, _LOGIN, $this->_getAllParams());
        if ($this->auth->hasIdentity()) {
            Log::debug($this, "user authorized");
            $this->_helper->Messenger('You authorised');
            $this->_redirect($_SERVER[HTTP_REFERER]);
        }
        $action = $this->_helper->Url->url(array(CONTROLLER => AUTH, ACTION => _LOGIN), AUTH, true);
        $form = new Site_Form_Auth($action);
        $this->view->headline = $this->view->translate('auth.title');
        if (!$this->getRequest()->isPost()) { //Простое отображение формы
            Log::debug($this, "loginAction - is not Post");
            $form->setDefault(PAGE, $_SERVER[HTTP_REFERER]);
        }
        else {
            Log::debug($this, "loginAction - is Post"); //Post запрос
            if ($form->isValid($_POST)) {
                try {
                    $user = $this->auth->authenticate($form->getLogin(), $form->getPassword());
                    $form->attempt(0);
                    $this->_helper->Messenger('Welcome', $user->name);
                    $this->_redirect($form->getValue(PAGE));
                } catch (Site_Service_Exception $e) {
                    $form->addErrorMessage($e->getMessage());
                    $form->attempt();
                }
                //            }else if (count($form->getErrors('code')) > 0) {
                //                $this->_helper->Messenger('Authentication failed');
                //                $this->_redirect($form->getValue(PAGE));
            }
        }
        Log::debug($this, "loginAction - 3");
        $this->view->form = $form;
    }

    public function logoutAction() {
        Log::info($this, _LOGOUT, $this->_getAllParams());
        $this->auth->logout();
        $this->_redirect('/');
    }

}



