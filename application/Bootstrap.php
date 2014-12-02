<?php
require_once 'configs/constant.php';
require_once 'services/Functions.php';
require_once 'services/Log.php';
require_once 'configs/Acl.php';



class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    public function _initAutoload() {
        $autoloader = new Zend_Application_Module_Autoloader(
            array(
                'namespace' => 'Site_',
                'basePath' => APPLICATION_PATH)
        );
        $autoloader->addResourceType('viewmarkup', 'views/markups', 'View_Markup');
        return $autoloader;
    }


    public function _initLogger() {
        $this->bootstrap('Log');
        Log::init($this->getResource('Log'));
    }

    public function _initAuth() {
        $this->bootstrap('Logger');
        $acl = new Site_Configs_Acl();
        //@todo
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
        $role=Site_Service_Registry::get(ACCESS)->getRole();
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($role);

        Log::debug($this, "initAuth");
    }

    public function _initLocaleAll() {
        $this->bootstrap('Locale');
        $encoding = $this->_options['siteSettings']['encoding'];
        Zend_Markup_Renderer_RendererAbstract::setEncoding($encoding);
        $locale = $this->_options['siteSettings']['locale'];
        setLocale(LC_ALL, "$locale.$encoding");
    }

    public function _initCache() {
        $this->bootstrap('Logger');
        $this->bootstrap('View');
        $this->bootstrap('FrontController');
        $this->bootstrap('CacheManager');
        $cacheManager = $this->getResource('CacheManager');
        $view = $this->getResource('View');
        $cache = array();
        $cache['output'] = $cacheManager->getCache('cache_output');
        $view->cache = $cache['output'];
        //Zend_Registry::set('Site_Cache_Output', $cache['output']);

        $cache['core'] = $cacheManager->getCache(CACHE_CORE);
        $this->frontController->setParam(CACHE_CORE, $cache['core']);
        Zend_Registry::set(CACHE_CORE, $cache['core']);

        $cache['db'] = $cacheManager->getCache('cache_db');
        $this->frontController->setParam('cache_db', $cache['db']);
        Zend_Db_Table_Abstract::setDefaultMetadataCache($cache['db']);
        Zend_Translate::setCache($cache['db']);

        Log::debug($this, "initCache");
        return $cache;
    }

    public function _initHead() {
        $this->bootstrap('View');
        $view = $this->view;
        $view->headTitle("Риф Закиров - Стихи")->setSeparator(" / ");
        $view->headMeta()
        ->appendHttpEquiv("Content-Type", "text/html; charset=UTF-8")
        ->appendHttpEquiv("pragma", "no-cache")
        ->appendName("verify-v1", "YiTL8EaS5FfRchKfBDeT5eozChrY6LyrnPYmQYoqmlg=")
        ->appendName("yandex-verification", "4475fd4d6a0a0ef0")
        ->appendName("title", "Риф Закиров - стихи. Авторские страницы")
        ->appendName("copyright", "(C) Закиров Рустем")
        ->appendName("author", "Риф Закиров")
        ->appendName("developed by", "Закиров Рустем")
        ->appendName("target", "")
        ->appendName("ROBOTS", "INDEX,FOLLOW");
        $view->headLink()->appendAlternate('/rss.xml', 'application/rss+xml', 'Риф Закиров : Лента - RSS feed');
        $view->headLink()->appendStylesheet('/css/style.css');
        $view->headLink()->appendStylesheet('/css/style-sidebar.css');
        $view->headLink()->appendStylesheet('/css/style-node.css');
        $view->headLink()->appendStylesheet('/css/style-form.css');

        $view->placeholder(KEYS)->setSeparator(' ')
        ->setPrefix('cтихи четверостишия поэзия ');
        $view->placeholder(DESC)->setSeparator(' ')
        ->setPrefix('Риф Закиров : ');
        Log::debug($this, "initDoctype");
    }

    public function _initRoutes() {
        $this->bootstrap('Logger');
        $this->bootstrap('FrontController');
        $router = $this->getResource('FrontController')->getRouter();
        $router->removeDefaultRoutes();
        $dir = $this->_options['siteSettings']['routes'];
        $config = new Zend_Config_Ini($dir);
        $router->addConfig($config, 'routes');
        Log::debug($this, "initRoutes");
        return $router;
    }

    public function _initTwitter() {
        $this->bootstrap('FrontController');
        $token_config = $this->_options['siteSettings']['twitter']['token'];
        $token = new Zend_Oauth_Token_Access;
        $token->setParams($token_config);
        $config = $this->_options['siteSettings']['twitter']['consumer'];
        $config['accessToken'] = $token;
        $this->getResource('FrontController')->setParam('twitter',$config);
        Log::debug($this, "initTwitter");
        return $config;
    }

    public function _initTranslator() {
        $this->bootstrap('Logger');
        $this->bootstrap('Translate');
        Zend_Validate_Abstract::setDefaultTranslator($this->translate);
        Zend_Form::setDefaultTranslator($this->translate);
        Log::debug($this, "initTranslator");
    }

    public function _initNavigation() {
        $this->bootstrap('Logger');
        $this->bootstrap('View');
        $this->bootstrap('Routes');
        $this->bootstrap('Cache');
        $cache = $this->getResource('Cache');
        $view = $this->view;

        if (!($navigation = $cache['core']->load(MENU))) {

            $dirs = $this->_options['siteSettings'][NAVIGATION];
            $navigation = array();
            foreach ($dirs as $key => $dir) {
                $config = new Zend_Config_Ini($dir);
                $container = new Zend_Navigation($config->pages);
                $navigation[$key] = $container;
            }

            $this->bootstrap('Db');
            $container = $navigation[MENU];
            $main_page = $container->findOneById(MAIN);
            //$rank_page = $container->findOneById(RANK);
            $term_top_page = $container->findOneById(TERMS);
            Log::debug($this, "classifier_page = $term_top_page");
            $this->initTaxonomyNavigation($main_page);

            //@name save in cache
            $cache['core']->save($navigation, MENU, array(MENU, CLASSIFIER, TERMS, TERM));
        }

        Zend_Registry::set(NAVIGATION, $navigation);

        Zend_Registry::set('Zend_Navigation', $navigation[MENU]);
        $view->navigation($navigation[MENU]);

        Log::debug($this, "initNavigation");
        return $navigation[MENU];
    }


    private function initTaxonomyNavigation($main_page)
    { //$classifiers = Site_Model_Classifier::getAll();
        $service = Site_Service_Registry::get(TAXONOMY);
        //@name create Classifier menu, Heads menu and Rank menu
        foreach ($service->getClassifiers() as $classifier) {
            $terms_page = new Zend_Navigation_Page_Mvc(array(
                ID => CLASSIFIER . $classifier->id,
                CONTROLLER => CLASSIFIER,
                ACTION => _OPEN,
                LABEL => $classifier->name,
                TITLE => $classifier->desc,
                PARAMS => array(ID => $classifier->id),
                'link' => false,
                ROUTE => CLASSIFIER,
                'priority' => "0.0",
            ));
            $main_page->addPage($terms_page);

            if ($classifier->code == TAG) {
                $terms = $service->getTerms($classifier, true);
                //@name create Tags menu
                $list_tags = new Zend_Tag_ItemList(); //shuffle($tags_array);
                foreach ($terms as $term) {
                    $list_tags[$term->id] = new Zend_Tag_Item(array(TITLE => $term->name, 'weight' => $term->count));
                }
                $list_tags->spreadWeightValues(array(75, 80, 85, 90, 94, 98, 102, 105, 108,
                    111, 113, 115, 117, 119, 121, 123, 125, 127, 129, 131, 133, 135, 137, 139, 141, 143, 145));
                //@name TAGS page add
                foreach ($terms as $term) {
                    $size = $list_tags[$term->id]->getParam('weightValue');
                    $title = empty($term->desc) ? $term->name : $term->desc;
                    $terms_page->addPage(array(
                        ID => TERM . $term->id,
                        //LABEL => ucfirst($term->name),
                        CONTROLLER => TERM,
                        ACTION => _OPEN,
                        LABEL => $term->name,
                        TITLE => $term->title . ' (' . $term->count . ')',
                        TEXT => $term->desc,
                        PARAMS => array(ID => $term->name),
                        'checkbox' => array(NAME => TERM . "[{$term->id}]", VALUE => $term->id),
                        'priority' => '0.6',
                        'changefreq' => 'weekly',
                        'style' => "font-size:$size%", //
                        ROUTE => TAG
                    ));

                }
            } else {
                $terms = $service->getTerms($classifier, true);
                if ($classifier->code == HEAD)
                    foreach ($terms as $term)
                        $terms_page->addPage(array(
                            ID => TERM . $term->id,
                            CONTROLLER => TERM,
                            ACTION => _OPEN,
                            LABEL => $term->name,
                            TITLE => $term->title,
                            TEXT => $term->desc,
                            PARAMS => array(ID => $term->id),
                            'checkbox' => array(NAME => TERM . "[{$term->id}]", VALUE => $term->id),
                            'priority' => '0.9',
                            'changefreq' => 'weekly',
                            ROUTE => HEAD
                        ));

                else
                    foreach ($terms as $term)
                        $terms_page->addPage(array(
                            ID => TERM . $term->id,
                            CONTROLLER => TERM,
                            ACTION => _OPEN,
                            LABEL => $term->name,
                            TITLE => $term->title . ' (' . $term->count . ')',
                            PARAMS => array(ID => $term->id),
                            'checkbox' => array(NAME => TERM . "[{$term->id}]", VALUE => $term->id),
                            'priority' => '0.3',
                            'changefreq' => 'weekly',
                            ROUTE => TERM
                        ));

            }
        }
    }


}

