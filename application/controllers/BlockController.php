<?php

class BlockController extends Zend_Controller_Action {
    private $cache_db;
    private $cache_core;
    private $access, $nodes; //Сервисы

    public function init() {
        $this->cache_db = $this->getInvokeArg('cache_db');
        $this->cache_core = $this->getInvokeArg('cache_core');
        $this->access = Site_Service_Registry::get(ACCESS);
        $this->nodes = Site_Service_Registry::get(NODE);
    }

    /**
     * Пустой Блок
     * (подчиненный блок колонка)
     */
    public function emptyAction() { $this->_helper->viewRenderer->setNoRender();}


    /**
     * Блок HEADER
     * (шапка, самый верх)
     */
    public function headerAction() {
        Log::info($this, 'header');
       if (!$this->_helper->Cache('block_header', NODES)) {
            $this->view->nodes = $this->nodes->getNodesByTerms(array(CAPTURE_TERM_ID),null,false);
       }
        $this->render(null, 'header');
    }

    /**
     * Блок рисунок
     * (центральная колонка)
     */
    public function pictureAction() {
        Log::info($this, 'picture');
        $this->view->picture = "teamsters.gif";
        $title = 'Пока живы тоска и Удивление, '
                . 'Не буду обделен я Вдохновением. '
                . 'Я - дерзкий раб, я - укротитель слов, '
                . 'Завидуйте погонщики ослов.';
        $this->view->title=$title;
        $this->render(null, 'center');
    }

    /**
     * Блок Хлебные крошки
     * (центральная колонка)
     */
    public function breadcrumbsAction() {
        Log::info($this, 'breadcrumbs');
        $this->render(null, 'center');
    }


    /**
     * Блок PHPINFO
     * (подчиненный блок колонка)
     */
    public function phpinfoAction() {
        Log::info($this, 'phpinfo');
        $this->render(null, 'sub');
    }

    /**
     * Блок количество стихов на сайте
     * (правая колонка)
     */
    public function countAction() {
        Log::info($this, 'count');
        //@name getNode
        if (!$this->_helper->Cache('block_count', NODES)) {
            $this->view->headline = $this->view->translate('verse.count');
            $this->view->publish = $this->nodes->getNodesCount(VERSE, true);
            $this->view->all = $this->nodes->getNodesCount(VERSE, false);
        }
        $this->render(null, 'right');
    }

    /**
     * Блок случайный стих
     * (правая колонка)
     */
    public function randomAction() {
        Log::info($this, 'random');
        //@name getNode
        $role = $this->access->getRole();
        $cache_name = $role . '_random';
        $node = $this->cache_core->load($cache_name);

        if (empty($node) || (isset($node) && isset($this->navigate_session->random)
                && $node->id = $this->navigate_session->random)) {
            $node = $this->nodes->getNodeRandom(VERSE);
            //$node->terms;
            $this->cache_core->save($node, $cache_name, array('random'));
        }
        $this->view->node = $node;
        $this->navigate_session->random = $node->id;
        $this->view->headline = $this->view->translate('verse.random');
        $this->render(null, 'right');
    }

    /**
     * Блок твиттера список стихов
     * (правая колонка)
     */

    public function twitterAction()
    {
        Log::info($this, 'tweet');
//        if (!$this->_helper->Cache('twitter', MAIN)) {
//            $config=$this->getInvokeArg('twitter');
//            $this->view->headline = 'Twitter';
//            $twitter = new Zend_Service_Twitter($config);
//            $this->view->timeline   = $twitter->status->userTimeline();
//            $this->view->response   =  $twitter->directMessage->messages();
//            $this->view->followers   =  $twitter->status->followers();
//        }
        if (APPLICATION_ENV == 'production') {
             $this->view->headline = $this->view->translate('twitter');
             $this->render(null, 'right');
        } else{
             $this->_helper->viewRenderer->setNoRender();
        }

    }

    /**
     * Блок FOOTER
     * (низ страницы)
     */
    public function statisticAction() {
        Log::info($this, 'statistic');
        if (!$this->access->hasIdentity() && APPLICATION_ENV == 'production') {
            $this->renderScript('banner/google.analytics.phtml', 'script');
            $this->renderScript('banner/yandex.counter.phtml', 'statistic');
            $this->renderScript('banner/yandex.informer.phtml', 'statistic');
            $this->renderScript('banner/yandex.metrika.phtml', 'statistic');
            $this->renderScript('banner/rambler.phtml', 'statistic');
            $this->renderScript('banner/mail.phtml', 'statistic');
            $this->renderScript('banner/tatarstan.phtml', 'statistic');
        }else{
           $this->_helper->viewRenderer->setNoRender();
        }

    }

}