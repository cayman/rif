<?php
class CommentController extends Zend_Controller_Action {
    //private $navigate;
    private $access, $nodes, $comments; //Сервисы

    public function init() {
        $this->access = Site_Service_Registry::get(ACCESS);
        $this->nodes = Site_Service_Registry::get(NODE);
        $this->comments = Site_Service_Registry::get(COMMENT);
        //$this->navigate = new Zend_Session_Namespace(SITE_SELECT);
    }


    public function listAction() {
        //@name Widgets
        $this->_helper->Stack->simpleTemplate();

        Log::info($this, _OPEN, $this->_getAllParams());
        //@name getParams
        $page = $this->_getParam(PAGE, 1);

        //@name get Items
        if (!$this->_helper->Cache( COMMENTS . '_' . $page, COMMENT)) {
            $this->view->paginator = $this->comments->getComments(null,null,true);
            $this->view->paginator->setCurrentPageNumber($page);
        }

        //@name setHeaders
        $this->view->headline = $this->view->translate('You comments');
        $this->view->headTitle($this->view->headline);
        $this->view->placeholder(KEYS)->append($this->view->translate('comments'));
        $this->view->placeholder(DESC)->append($this->view->translate('You comments to poetry'));



    }


    public function nodeAction()
    {
        Log::info($this, 'node', $this->_getAllParams());
        //@name getComments
        $id = $this->_getParam(ID);
        if (isset($id)) {
            $page = $this->_getParam(PAGE, 1);
            $this->view->paginator = $this->comments->getComments($id,null,true);
            $this->view->paginator->setCurrentPageNumber($page);
            $this->view->headline = $this->view->translate('You comments');
        }
    }


    public function addAction() {
        //@name Widgets
        if($this->_request->isXmlHttpRequest())
            $this->_helper->layout->setLayout('ajax');
        else
            $this->_helper->Stack->baseTemplate();

        Log::info($this, _ADD, $this->_getAllParams());
        $id = (int) $this->_getParam(ID);

        //@name getNode
        $node = $this->nodes->getNode($id);
        //@name createForm
        $action = $this->_helper->Url->url(array(CONTROLLER => COMMENT, ACTION => _ADD, ID => $node->id), COMMENT, true);

        $form = new Site_Form_CommentAdd($action);

        //@process form
        if (!$this->getRequest()->isPost()) {
            Log::info($this, "addAction - is not Post - from view");
            $form->setDefault(PAGE, $_SERVER[HTTP_REFERER]);
            if ($this->access->hasIdentity())
                $form->setDefault(NAME, $this->access->getUser()->name);

        } else {
            //@name post Form
            Log::debug($this, "addAction - is Post"); //Post запрос
            if ($form->isValid($_POST)) {
                Log::debug($this, "addAction - Post value valid");
                $comment = new Site_Model_Comment(array('node_id' => $id));
                $form->copy($comment);
                $this->comments->addComment($comment);

                $this->_helper->Messenger('Thanks for comment');
                $this->view->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array(NODE . $id, COMMENT));
                $this->_redirect($form->getValue(PAGE));

//            } else if (count($form->getErrors('code')) > 0) {
//                $this->_helper->FlashMessenger('403 Forbidden!');
//                $this->_helper->FlashMessenger('Cross-Site Request Forgeries!');
//                $this->_redirect($form->getValue(PAGE));
            }
        }

        //@name setHeaders
        $this->view->node = $node;
        $this->view->form = $form;
        $this->view->headline = $this->view->translate('You comment');
        $this->view->headTitle($node->desc);

        //@name Widgets
        $this->_helper->Stack->add('open', 'node', null, array(ID => $id, 'stack' => true));

        $this->render(FORM, 'sub');
    }

    public function editAction() {
        //@name Widgets
        if($this->_request->isXmlHttpRequest())
            $this->_helper->layout->setLayout('ajax');
        else
            $this->_helper->Stack->baseTemplate();

        Log::info($this, _EDIT, $this->_getAllParams());
        $id = (int) $this->_getParam(ID);

        //@name getComment and Node
        $comment = $this->comments->getComment($id);
        $node = $this->nodes->getNode($id);

        //@name createForm
        $action = $this->_helper->Url->url(array(CONTROLLER => COMMENT, ACTION => _EDIT, ID => $node->id), COMMENT, true);
        $form = new Site_Form_CommentEdit($action);

        //@process form
        if (!$this->getRequest()->isPost()) {
            Log::debug($this, "editAction - is not Post - show form");
            $form->populate($comment);
            $form->setDefault(PAGE, $_SERVER[HTTP_REFERER]);

        } else {
            //@name post Form            
            Log::debug($this, "editAction - is Post method");

            if ($form->isValid($_POST)) {
                $form->copy($comment);
                $this->comments->editComment($comment);

                $this->_helper->Messenger('Changes made');
                $this->view->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array(COMMENT));
                $this->_redirect($form->getValue(PAGE));
            }
        }

        //@name activate menu items
        $this->_helper->Navigation($node);

        //@name setHeaders
        $this->view->node = $node;
        $this->view->form = $form;
        $this->view->headline = $this->view->translate('Edit comment');
        $this->view->headTitle($node->desc);

        //@name Widgets
        $this->_helper->Stack->add('open', 'node', null, array(ID => $id, 'stack' => true));

        $this->render(FORM, 'sub');
    }


    public function deleteAction() {
        Log::info($this, _DELETE, $this->_getAllParams());
        $id = (int) $this->_getParam(ID);
        //@name getComment
        $comment = $this->comments->getComment($id);
        //@name save
        $this->comments->deleteComment($comment);
        $this->_helper->Messenger('Posted in spam');
        $this->view->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array(COMMENT));
        $this->_redirect($_SERVER[HTTP_REFERER]);
    }


}
