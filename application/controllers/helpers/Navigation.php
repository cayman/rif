<?php
/**
 * Помощник действия для добавления элемента в sidebar
 *
 * @uses Zend_Controller_Action_Helper_Abstract
 */
class Site_Controller_Helper_Navigation extends Zend_Controller_Action_Helper_Abstract
{
    private $menu;
    private $navigate;

    public function init()
    {
        $this->menu = Zend_Registry::get('Zend_Navigation');
        $this->navigate = new Zend_Session_Namespace(SITE_SELECT);
    }

    public function get($pageId)
    {
        return $this->menu->findOneById($pageId);
    }

    public function getActive()
    {
        //$this->menu = Zend_Registry::get('Zend_Navigation');
        return $this->menu->findOneByActive(true);
    }

    /**
     * Добавляем активным нужную страницу
     * Необходимо для отображения Хлебных крошек
     * @param  $pageId
     * @param  $pageNum
     * @return void
     */
    public function setActive($pageId)
    {
        Log::debug($this, "setActive=$pageId");
        $pages = $this->menu->findAllById($pageId);
        foreach($pages as $page){
          $page->setActive(true);
        }
    }

    /** Актиировать страницу из сессии
     * @param  $prefix
     * @param  $items
     * @return Site_Controller_Helper_Navigation
     */
    public function activate($node)
    {
        $currentAction=$this->getActionController();
        if($currentAction->getRequest()->getParam('stack') == null){
            $active = $this->getActive();
            if (!isset($active)) {
                $term = $node->getMainTerm();
                if(isset($term))
                    $this->setActive(TERM . $term->id);
            }
        }
    }



    /** Актиировать страницу из сессии
     * @param  $prefix
     * @param  $items
     * @return Site_Controller_Helper_Navigation
     */
//    public function activateOld($node,Zend_Session_Namespace $session)
//    {
//        Log::debug($this, $session->prefix, $session->param);
//
//        if($session->prefix==SEARCH || $session->prefix==COMMENT) //Вход в узел из поиска по словам
//            $this->setActive($session->prefix,$session->page);
//        else if($session->prefix==TERM){ //Вход в узел из поиска по таксономии
//            $terms=$session->terms;
//            if (isset($terms) && is_array($terms) && count($terms) == 1) //Активитуем сохраненную таксономию
//                $this->setActive(TERM . $terms[0],$session->page);
//            else
//                $this->setActive(SEARCH,$session->page);
//        }else{  //Вход в узел из вне (пустая сессия)
//            $head=$node->getTerm(HEAD);
//            if(isset($head[0]))
//                $this->setActive(TERM . $head[0]->id,$session->page);
//        }
//
//        return $this;
//    }


    /**Добавления номера странцы в Breadcrumbs
     * @param  $activePage
     * @return
     */
//    private function activateParamPage($view, $page)
//    {
//        if ($page == 1) return $this;
//        $activePage = $view->Navigation()->findActive($view->Navigation()->getContainer());
//        if (isset($activePage[PAGE])) {
//            $childPage = clone $activePage[PAGE];
//            $childPage->id = PAGE . $page;
//            $label = $view->translate(PAGE) . ' ' . $page;
//            $childPage->label = $label;
//            $params = $childPage->params;
//            $params[PAGE] = $page;
//            $childPage->params = $params;
//            Log::debug($this, "addBreadcrumbsTermPageNum", $childPage->params);
//            $childPage->active = true;
//            $activePage[PAGE]->addPage($childPage);
//        }
//        return $this;
//    }

    /**
    /**Добавления Материала в Breadcrumbs
     * @param  $activePage
     * @return
     */
//    public function addNode($view, $node)
//    {
//        $this->addPage($view,
//                       array(ROUTE => NODE, ID => $node->id, LABEL => $node->title, TITLE => $node->desc));
//    }

//    public function addPage($view, array $param)
//    {
//        $activePage = $view->Navigation()->findActive($view->Navigation()->getContainer());
//        Log::debug($this, "activatePage", $activePage);
//        if (isset($activePage[PAGE])) {
//            $activePage[PAGE]->addPage(array(
//                                            ID => $param[ROUTE] . $param[ID],
//                                            LABEL => $param[LABEL],
//                                            TITLE => $param[TITLE],
//                                            'active' => true,
//                                            PARAMS => array(ID => $param[ID]),
//                                            ROUTE => $param[ROUTE]
//                                       ));
//        }
//    }


    /***


    /**
     * Паттерн Стратегии: вызываем помощник как метод брокера
     *
     * @param  string $name
     * @param  array|Zend_Config $options
     * @return Zend_Form
     */
    public function direct($node)
    {
        return $this->activate($node);
    }
}

