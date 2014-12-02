<?php
/**
 * User: Zakirov
 * Date: 15.03.2010
 * Time: 17:05:20
 * To change this template use File | Settings | File Templates.
 */

class NodeController extends Zend_Controller_Action
{

    private $navigate;
    private $nodes,$types;   //Сервисы

    public function init()
    {
        $this->navigate = new Zend_Session_Namespace(SITE_SELECT);
        $this->nodes = Site_Service_Registry::get(NODE);
        $this->types = Site_Service_Registry::get(TYPE);

    }

    /**
     * Открытие
     * @return void
     */
    public function openAction()
    {
        //@name Widgets
        $this->_helper->Stack->baseTemplate();
        Log::info($this, _OPEN, $this->_getAllParams());
        //@name getParams
        $id = (int)$this->_getParam(ID, 1);
        //@name getNode
        $node = $this->nodes->getNode($id);
        if (!$this->_helper->Cache(NODE . $id, NODES)) {
            $this->view->next = $this->nodes->getNextNode($node);
            $this->view->prev = $this->nodes->getPrevNode($node);
            //@name setHeaders
            $this->view->node = $node;
        }
        //@name Widgets
        $this->view->headTitle($node->title);
        $this->_helper->Navigation($node);
        if ($node->node_comment) {
            $this->_helper->Stack
                ->add('node', 'comment')
                ->add('add', 'comment', null, array(ID => $id, 'stack' => true));
        }

    }

    /**
     * Редактирование
     * @return void
     */
    public function editAction()
    {
        //@name Widgets
        if ($this->_request->isXmlHttpRequest())
            $this->_helper->layout->setLayout('ajax');
        else
            $this->_helper->Stack->baseTemplate();

        Log::info($this, _EDIT, $this->_getAllParams());
        //@name getParams
        $id = (int)$this->_getParam(ID);

        //@name getNode
        $node = $this->nodes->getNode($id);
        $type = $this->types->getType($node->type_id);

        //@name createForm
        $action = $this->_helper->Url->url(array(CONTROLLER => NODE, ACTION => _EDIT, ID => $node->id), NODE, true);
        $form = new Site_Form_Node($type, $action);

        //@process form
        if (!$this->getRequest()->isPost()) { //Get Form
            Log::debug($this, "editAction - is not Post - show form");
            $form->populate($node);
            $form->setPage($_SERVER[HTTP_REFERER]);

        } else {
            //@name post Form
            Log::debug($this, "editAction - is Post"); //Post запрос
            if ($form->isValid($_POST)) {
                $old_date = $node->date;
                $form->copy($node);
                //$node->correctDate($old_date);
                //@name save to DB
                $this->nodes->editNode($node);
                $this->_helper->Messenger('Changes made');
                $this->view->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array(MAIN, NODES));
                $this->_redirect($form->getValue(PAGE));
                Log::info($this, "OK");
            }
        }
        //@name setHeaders
        $this->view->form = $form;
        $this->view->headline = $this->view->translate('node.edit');
        $this->view->headline .= ' "' . $type->name . '"';
        $this->view->headTitle($this->view->headline);
        //@name Widgets
        $this->_helper->Navigation($node);
        $this->_helper->Stack->add('open', 'node', null, array(ID => $id, 'stack' => true));

        $this->render(FORM, 'sub');
    }

    /**
     * Создание  нового из существующего
     * @return void
     */
    public function copyAction()
    {
        //@name Widgets
        if ($this->_request->isXmlHttpRequest())
            $this->_helper->layout->setLayout('ajax');
        else
            $this->_helper->Stack->baseTemplate();

        Log::info($this, _COPY, $this->_getAllParams());
        //@name getParams
        $id = $this->_getParam(ID, 1);
        //@name getNode
        $node = $this->nodes->getNode($id);
        $type = $this->types->getType($node->type_id);

        //@name createForm
        $action = $this->_helper->Url->url(array(CONTROLLER => NODE, ACTION => _ADD, ID => $type->id), NODE, true);
        $form = new Site_Form_Node($type, $action);

        //@process form
        Log::debug($this, "copyAction - show form");
        $date = new Zend_Date($node->date);
        $date->add('00:32:17', Zend_Date::TIMES);
        $form->setDate($date);
        $form->setText(null);
        $form->setTerms($node->terms); //populate child subforms
        $form->setPage($_SERVER[HTTP_REFERER]);

        //@name setHeaders
        $this->view->headline = $this->view->translate('node.add');
        $this->view->headline .= ' "' . $type->name . '"';
        $this->view->headTitle($this->view->headline);
        $this->view->form = $form;

        //@name Widgets
        $this->_helper->Navigation($node);

        $this->render(FORM, 'sub');
    }
    
    /**
     * Создание нового
     * @return void
     */
    public function addAction()
    {
        //@name Widgets
        if ($this->_request->isXmlHttpRequest())
            $this->_helper->layout->setLayout('ajax');
        else
            $this->_helper->Stack->baseTemplate();

        Log::info($this, _ADD, $this->_getAllParams());
        //@name getParams
        $type_id = $this->_getParam(ID, 1);

        //@name getType
        $type = $this->types->getType($type_id);
        $node = new Site_Model_Node(array('type_id' => $type_id));

        //@name createForm
        $action = $this->_helper->Url->url(array(CONTROLLER => NODE, ACTION => _ADD, ID => $type->id), NODE, true);
        $form = new Site_Form_Node($type, $action);

        //@process form
        if (!$this->getRequest()->isPost()) { //Get Form
            Log::debug($this, "addAction - is not Post - show form");
            $form->populate($node);
            $form->setPage($_SERVER[HTTP_REFERER]);
        } else {
            Log::debug($this, "addAction - is Post");
            //@name post Form
            if ($form->isValid($_POST)) {
                Log::debug($this, "addAction - form isValid");
                $form->copy($node);
                $exists = $this->nodes->getNodesIdentity($node);
                if (count($exists) == 0) {
                    //@name save to DB
                    $this->nodes->addNode($node);
                    $this->_helper->Messenger('Material added');
                    $this->view->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array(MAIN, NODES));
                    $this->_redirect($form->getValue(PAGE));
                } else {
                    Log::debug($this, "addAction - Identity Node found");
                    $this->view->headline = $this->view->translate('Attention') . '<br/>';
                    $this->view->headline .= $this->view->translate('This material is already in the database');
                    $this->view->items = $exists;
                    $this->render(_LIST, 'info');
                }

            }
        }

        //@name setHeaders
        $this->view->headline = $this->view->translate('node.add');
        $this->view->headline .= ' "' . $type->name . '"';
        $this->view->headTitle($this->view->headline);
        $this->view->form = $form;

        //@name Widgets
        $this->_helper->Navigation($node);

        $this->render(FORM, 'sub');
    }

   /**
    * Изменение свзанных терминов
    * @return void
    */

    public function termAction()
    {
        //@name Widgets
        if ($this->_request->isXmlHttpRequest())
            $this->_helper->layout->setLayout('ajax');
        else
            $this->_helper->Stack->baseTemplate();

        Log::info($this, _OPEN, $this->_getAllParams());
        $id = $this->_getParam(ID, 1);

        //@name getNode
        $node = $this->nodes->getNode($id);
        $type = $this->types->getType($node);
        //@name createForm
        $action = $this->_helper->Url->url(array(CONTROLLER => NODE, ACTION => TERM, ID => $id), NODE, true);
        $form = new Site_Form_NodeTerms($type, $action);

        if (!$this->getRequest()->isPost()) { //Get Form
            $form->populate($node);
            $form->setDefault(PAGE, $_SERVER[HTTP_REFERER]);
        } else {
            //@name post Form
            Log::debug($this, "editAction - is Post"); //Post запрос
            if ($form->isValid($_POST)) {
                //$node->terms_old = $node->terms;
                $node->terms = $form->getTerms();
                $this->nodes->saveNodeTerms($node);
                $this->view->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array(MAIN, NODES));
                $this->_redirect($form->getPage());
                Log::info($this, "OK");
            } else
                $this->view->form = $form;
        }
        //@name setHeaders
        $this->view->headline = $this->view->translate('terms.edit');
        $this->view->headline .= ' "' . $type->name . '"';
        $this->view->headTitle($this->view->headline);
        $this->view->form = $form;

        //@name Widgets
        $this->_helper->Navigation($node);

        $this->render(FORM, 'sub');
    }


   /**
    * Изменение рейтинга
    * @return void
    */

      public function rankAction()
    {
        //@name Widgets
        if ($this->_request->isXmlHttpRequest())
            $this->_helper->layout->disableLayout();

        Log::info($this, 'rank', $this->_getAllParams());
        //@name getParams
        $id = $this->_getParam(ID);
        $operation = (int)$this->_getParam('operation', 0);
        if ($this->isRankSet($id) || is_bot($_SERVER['HTTP_USER_AGENT'])) $operation = 0;
        //@name getNode
        $node = $this->nodes->getNode($id);
        $term = Site_Service_Registry::get(TAXONOMY)->getTerm(RANK_TERM_ID);
        Log::info($this, "operation=$operation");
        $value = (int)$this->nodes->addNodeTermValue($node, $term, $operation);
        $this->view->rate = $term->name . ' [' . $value . ']';

    }


    protected function isRankSet($id)
    {
        $session = new Zend_Session_Namespace('Site_Rank');
        $var = NODE . $id;
        if (isset($session->$var)) {
            //  $this->_helper->Messenger("You have already voted");
            return true;
        } else {
            $session->$var = true;
            return false;
        }
    }



}
