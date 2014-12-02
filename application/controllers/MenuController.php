<?php

class MenuController extends Zend_Controller_Action
{
    private $access;

    public function init()
    {
        $this->access = Site_Service_Registry::get(ACCESS);
    }

    /**
     * Меню глав (в правой части)
     * @return void
     */
    public function headsAction()
    {
        Log::info($this, 'heads');
        if ($this->access->hasIdentity()) {
            //
            $activePage = $this->_helper->Navigation->getActive();
            if ($activePage->route == TERM || $activePage->route == TAG || $activePage->route == HEAD)
                $this->_helper->Cache('menu_classifier_' . $activePage->id, TERM);
            else
                $this->_helper->Cache('menu_classifier', TERM);
            $this->render(CLASSIFIER, 'right');
        }
        else {
            $activePage = $this->_helper->Navigation->getActive();
            if ($activePage->route == HEAD)
                $this->_helper->Cache('menu_heads_' . $activePage->id, TERM);
            else
                $this->_helper->Cache('menu_heads', TERM);
            $this->render(null, 'right');
        }
    }

    /**
     * Меню тегов (в правой части)
     * @return void
     */
    public function tagsAction()
    {
        Log::info($this, 'tags');
        $activePage = $this->_helper->Navigation->getActive();
        if ($activePage->route == TAG)
            $this->_helper->Cache('menu_tags_' . $activePage->id, TERM);
        else
            $this->_helper->Cache('menu_tags', TERM);
        $this->render(null, 'right');
    }

    /**
     * Меню пользователя (в правой части)
     * @return void
     */
    public function userAction()
    {
        Log::info($this, 'user');
        $this->render(null, 'right');
    }

    public function searchAction()
    {
        Log::info($this, 'search');
        if (!$this->_helper->Cache('menu_search', NODES)) {
            $action = $this->_helper->Url->url(array(ACTION => SEARCH, CONTROLLER => TERM), SEARCH);
            $form = new Site_Form_Search($action);
            //            if (!$this->getRequest()->isPost()) { //Get Form
            //                if (Zend_Registry::isRegistered(SEARCH))
            //                    $form->setDefault(KEY, Zend_Registry::get(SEARCH));
            //                //$form->setPage(PAGE,$_SERVER[HTTP_REFERER]);
            //            }
            $this->view->form = $form;
        }
        $this->render(null, 'right');
    }

    public function sortAction()
    {
        Log::info($this, 'sort');
        $navigate = new Zend_Session_Namespace(SITE_SELECT);
        $this->view->items = array(ID, DATE, TEXT, 'desc', 'pic');
        $this->view->sort = Zend_Registry::get(ORDER);
        $this->render(null, 'right');
    }

    public function loginAction()
    {
        Log::info($this, 'user');
        if ($this->access->hasIdentity())
            $this->renderScript('block/user.phtml', 'right');
        else
            $this->render(null, 'right');
    }


}