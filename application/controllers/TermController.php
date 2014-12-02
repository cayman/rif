<?php
/**
 * User: Zakirov
 * Date: 15.03.2010
 * Time: 17:05:20
 * To change this template use File | Settings | File Templates.
 */
class TermController extends Zend_Controller_Action
{
    private $cache_core;
    private $taxonomy, $access, $nodes;

    private $navigate;
    private $cache_name;

    public function init()
    {
        $this->cache_core = $this->getInvokeArg('cache_core');
        $this->navigate = new Zend_Session_Namespace(SITE_SELECT);
        $this->taxonomy = Site_Service_Registry::get(TAXONOMY);
        $this->access = Site_Service_Registry::get(ACCESS);
        $this->nodes = Site_Service_Registry::get(NODE);
    }


    /** Выборка по Таксономии
     * @return void
     */
    public function openAction()
    {
        //@name Widgets
        $this->_helper->Stack->termTemplate();
        Log::info($this, _OPEN, $this->_getAllParams());
        //@name getParams
        $terms = $this->getTermsParam();
        $order = $this->getOrderParam();
        $page = $this->getPageParam();
        //@Сохраняем в сессию текущую таксономию
        $this->navigate->terms = $terms;
        Zend_Registry::set(TERMS, $terms);
        $this->navigate->prefix = TERM;
        $this->navigate->page = $page;
        //@name get Items
        if (!$this->_helper->Cache($this->cache_name . $order . '_' . $page, NODES)) {
            $this->view->paginator = $this->nodes->getNodesByTerms($terms, $order);
            $this->view->paginator->setItemCountPerPage(10);
            $this->view->paginator->setCurrentPageNumber($page);
        }
        //@name Widgets
        $this->view->headTitle($this->view->headline);

    }

    /** Печать
     * @return void
     */
    public function printAction()
    {   //@name Widgets
        $this->_helper->Layout()->setLayout(_PRINT);

        Log::info($this, _OPEN, $this->_getAllParams());
        //@name getParams
        $terms = $this->getTermsParam();
        $order = $this->getOrderParam();
        //@name get Items
         $this->view->items = $this->nodes->getNodesByTerms($terms, $order, false);
        //@name Widgets
         $this->view->headTitle($this->view->headline);

    }

    /**Поиск слова
     * @return void
     */
    public function searchAction()
    {
        //@name Widgets
        $this->_helper->Stack->searchTemplate();
        Log::info($this, _OPEN, $this->_getAllParams());
        //@name getParams
        $keys = $this->getKeysParam();
        $order = $this->getOrderParam();
        $page = $this->getPageParam();
        //@Сохраняем в сессию текущее слово
        $this->navigate->prefix = SEARCH;
        $this->navigate->keys = $keys;
        $this->navigate->page = $page;
        Zend_Registry::set(SEARCH, mb_strtolower($keys)); //Сохраняем в реестр
        //@name get Items
        $this->view->headline = LAQUO . $keys . RAQUO; //@todo Заглавную букву
        $this->view->paginator = $this->nodes->getNodesByKeys($keys, $order);
        $this->view->paginator->setItemCountPerPage(10);
        $this->view->paginator->setCurrentPageNumber($page);

        //@name Widgets
        $this->view->headTitle($this->view->headline);

    }


    /**
     * Получение параметров Термины
     * @throws Site_Service_Exception
     * @return array|string
     */
    private function getTermsParam()
    {
         $classifier = $this->_getParam(CLASSIFIER,null);

        //@Фильтрация параметров
        if ($this->getRequest()->isPost()){
            $filter = new Site_Form_Filter_InputPostTerms();
            $param = $filter->filter($this->_getParam(TERM));
        }else{
            $filter = new Site_Form_Filter_InputGetTerms();
            $param = $filter->filter($this->_getParam(ID));
        }
        if(empty($param)) $param = $this->navigate->terms;

        //@Контроль параметров
        if (empty($param))
            throw new Site_Service_Exception('You must specify term', null, 61,null,$_SERVER[HTTP_REFERER]);

        Log::debug($this, 'Terms param=', $param);

        $this->cache_name = TERM;
        //@Поиск терминов в базе, формирование название,
        if (is_array($param)) {
            $terms = $this->taxonomy->getTermsArray($param,$classifier);
            foreach ($terms as $item) {
                $this->view->headline .= $item->name . ', ';
                $this->cache_name .= $item->id . '_';
                $this->_helper->Navigation->setActive(TERM.$item->id);
            }
            $this->view->terms =$terms;
        }
        else {
            $term = $this->taxonomy->getTerm($param, $classifier);
            $this->view->headline = $term->name;
            $this->cache_name .= $term->id . '_';
            $this->view->terms =array($term);
            $param = array($term->id);
        }
        return $param;
    }
    /**
     * Получение параметров Ключевые слова
     * @throws Site_Service_Exception
     * @return string
     */
    private function getKeysParam()
    {   $post=$this->getRequest()->isPost();
        //@Фильтрация параметров
        $filter = new Site_Form_Filter_InputKeys();
        $keys = $filter->filter($post?$this->_getParam(KEY):$this->_getParam(ID));
         if(empty($keys)) $keys = $this->navigate->keys;
        //@Контроль параметров
        if (empty($keys))
                throw new Site_Service_Exception('Not specified keyword',null,62,null,$_SERVER[HTTP_REFERER]);
        if (strlen($keys) < 4)
                throw new Site_Service_Exception('You must specify keyword',3,63,null,$_SERVER[HTTP_REFERER]);
        $this->_helper->Navigation->setActive(SEARCH);
        Log::debug($this, '$keys=', $keys);
        return $keys;
    }
    /**
      * Получение параметров Сортировки
     * @return mixed
     */
    private function getOrderParam()
    {
        $sort = $this->_getParam(SORT); //Устновка по умолчанию сортировки
        if(isset($sort)) $this->navigate->order = $sort;
        if(!isset($this->navigate->order))
            $this->navigate->order =  DATE;
        $order = $this->_getParam(ORDER, $this->navigate->order); //по умолчанию из сессии
        //@name saveOrder
        Zend_Registry::set(ORDER, $order);
        Log::debug($this, '$order=', $order);
        return $order;
    }

    /**
      * Получение параметров Номер страницы
     * @return mixed
     */
    private function getPageParam()
    {
        $page = $this->_getParam(PAGE, 1);
        return $page;
    }

    /**
     * Описание
     * @return void
     */
    public function infoAction() {

        //@name Widgets
        $this->_helper->Stack->termTemplate();
        Log::info($this, _OPEN, $this->_getAllParams());
        //@name getParams
        $id = $this->_getParam(ID);
        //@name getTerm
        $term = $this->taxonomy->getTerm($id);
        //@name setHeaders
        $this->view->term = $term;
        $this->view->headTitle($term->name);
        $this->view->headline = $this->view->translate('term.property');

        //@name Widgets
        $this->_helper->Navigation->setActive(TERM.$term->id);

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
        $class_id = $this->_getParam(ID,1);

        //@name get Classifier
        $classifier = $this->taxonomy->getClassifier($class_id);
        $term = new Site_Model_Term(array('class_id' => $class_id));

        //@name createForm
        $action = $this->_helper->Url->url(array(CONTROLLER => TERM, ACTION => _ADD, ID => $classifier->id), TERM, true);
        $form = new Site_Form_Term($action);

        //@process form
        if (!$this->getRequest()->isPost()) { //отображение формы
            Log::debug($this, "addAction - is not Post - show form");
            $form->populate($term);
            $form->setDefault(PAGE, $_SERVER[HTTP_REFERER]);
        } else {
            Log::debug($this, "addAction - is Post"); //Post запрос
            //@name post Form
            if ($form->isValid($_POST)) {
                $form->copy($term);
                $this->taxonomy->addTerm($term);

                $this->_helper->Messenger('Term was created');
                $this->cache_core->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array(MAIN, TERM, CLASSIFIER));
                $this->_redirect($form->getValue(PAGE));
            }
        }
        //@name setHeaders
        $this->view->form = $form;
        $this->view->headline = $this->view->translate('term.add');
        $this->view->headTitle($this->view->headline);

        //@name Widgets
        $this->_helper->Navigation->setActive(TERM.$term->id);

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

        Log::info($this, _COPY, $this->_getAllParams());
        //@name getParams
        $id = $this->_getParam(ID,1);
        //@name getTerm
        $term = $this->taxonomy->getTerm($id);
        //@name getData
        $action = $this->_helper->Url->url(array(CONTROLLER => TERM, ACTION => _ADD, ID => $term->class_id), TERM, true);
        $form = new Site_Form_Term($action);

        //@process form
        Log::debug($this, "copyAction - show form");
        $date = new Zend_Date();
        $form->setDate($date);
        $form->setClass($term->class_id);
        $form->setName($term->name);
        $form->setDesc($term->desc);
        $form->setPage($_SERVER[HTTP_REFERER]);

        //@name setHeaders
        $this->view->form = $form;
        $this->view->headline = $this->view->translate('term.add');
        $this->view->headTitle($this->view->headline);

        //@name Widgets
        $this->_helper->Navigation->setActive(TERM.$term->id);

        $this->render(FORM, 'sub');
    }

    /**
     * Редактирование термина
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
        //@name getTerm
        $term = $this->taxonomy->getTerm($id);

        //@name getData
        $action = $this->_helper->Url->url(array(CONTROLLER => TERM, ACTION => _EDIT, ID => $id), TERM, true);
        $form = new Site_Form_Term($action);

        if (!$this->getRequest()->isPost()) { //отображение формы
            Log::debug($this, "editAction - is not Post - show form");
            $form->populate($term);
            $form->setDefault(PAGE, $_SERVER[HTTP_REFERER]);
        } else {
            Log::debug($this, "editAction - is Post"); //Post запрос
            if ($form->isValid($_POST)) {
                $form->copy($term);
                Log::debug($this, "new value"); //Post запрос
                $this->taxonomy->editTerm($term);
                $this->_helper->Messenger('Changes made');
                $this->cache_core->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array(MAIN, TERM, CLASSIFIER));
                $this->_redirect($form->getValue(PAGE));
                Log::info($this, "OK");
            }
        }
        //@name setHeaders
        $this->view->form = $form;
        $this->view->headline = $this->view->translate('term.edit');
        $this->view->headTitle($this->view->headline);
        //@name Widgets
        $this->_helper->Navigation->setActive(TERM.$term->id);

        $this->render(FORM, 'sub');
    }


}

