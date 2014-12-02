<?php

class MainController extends Zend_Controller_Action
{
    private $nodes; //Сервисы
    private $desc;
    private $key; //Сервисы

    public function init()
    {
        $this->nodes = Site_Service_Registry::get(NODE);
        $this->_helper->ContextSwitch()
        ->setActionContext('feed', 'xml')
        ->setActionContext('map', 'xml')
        ->setDefaultContext('xml')
        ->initContext();
        $this->desc="Авторские страницы Жизненные заметки"
                  . "о жизни, о мудрости, о любви, о природе. "
                  . "Много интересных и неожиданных четверостиший. Поэзия";
        $this->key="Авторские страницы Жизненные заметки"
                   . "мудрости любви природе интересных неожиданных";
    }

    public function openAction()
    {
        //@name Widgets
        $this->view->headTitle($this->view->translate('New poems'));
        $this->view->headMeta()
                    ->appendName(DESC, $this->desc)
                    ->appendName(KEYS, $this->key);
        $this->_helper->Stack->frontTemplate();


        Log::info($this, _OPEN, $this->_getAllParams());
        if (!$this->_helper->Cache(MAIN, MAIN)) {
            $terms = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13); //select by HEAD_CLASS_ID
            $this->view->nodes = $this->nodes->getNodesLast($terms, 1);
            $this->view->headline = $this->view->translate('New poems');
        }
    }

    public function feedAction()
    {
        Log::info($this, 'feed', $this->_getAllParams());
        $mode= $this->_getParam('mode','rss');
        if (!$this->_helper->Cache('feed', MAIN)) {
            $terms = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13); //select by HEAD_CLASS_ID
            $nodes = $this->nodes->getNodesLast($terms, 5);
            $bbcode=Site_View_Markup_BBCode::factory(GUEST);
            $entries = array();
            foreach ($nodes AS $node) {
                $head=$node->getMainTerm();
                $date = new Zend_Date($node->date);
                $link = "http://rif.name/node/{$node->id}";
                $content=$bbcode->render(brief($node->text,3));
                $entry = array(
                    'title' => "{$node->title}",
                    'link' => $link,
                    'description' => "{$node->desc}",
                    'lastUpdate' => $date->toString(Zend_Date::TIMESTAMP),
                    'content' => $content.'...',
                    'category'     => array(
                        array('term' =>$head->title,
                          'scheme' => "http://rif.name/head/{$head->id}",
                        )
                    ),
                    'comments' => $link

                );
                array_push($entries, $entry);
            }

            // Create the array
            $channel = array(
                'title' => 'Риф Закиров',
                'link' => 'http://rif.name',
                'charset' => 'UTF-8',
                'language' => 'ru',
                'description' => $this->desc,
                'generator' => 'Rif.name',
                'author'      => 'Риф Закиров', // опциональный
                'email'       => 'mail@rif.name', // опциональный
                'copyright'   => 'Риф Закиров', // опциональный
                'entries' => $entries
            );

            // Import the array
            $feed = Zend_Feed::importArray( $channel, $mode);

            // Write the feed to a variable
            $this->view->feed = $feed->saveXML();

        }

    }

    public function mapAction() {
        Log::info($this, 'map', $this->_getAllParams());
        $this->_helper->Cache('sitemap', MAIN);
    }

    public function phpinfoAction() {
        Log::info($this, 'phpinfo', $this->_getAllParams());
        $this->_helper->ActionStack
                    ->actionToStack('phpinfo', 'block');
    }




}