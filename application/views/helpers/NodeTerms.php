<?php
class Site_View_Helper_NodeTerms extends Site_View_Helper_Html
{

    private $menuOptions = array('indent' => 4, 'ulClass' => 'links inline');
    private $node;
    private $menu;

    public function nodeTerms(Site_Model_Node $node)
    {
        $this->node = $node;
        $navigation = Zend_Registry::get(NAVIGATION);
        $this->menu = $navigation[TERM];
        return $this;
    }

    public function part($classes)
    {
        if (is_string($classes)) $classes = array($classes);
        $term_menu = new Zend_Navigation();
        $nodeTerms = $this->node->terms;
        foreach ($classes as $class) {
            if (array_key_exists($class, $nodeTerms)) {
                $terms = $nodeTerms[$class];
                $action = ($class == HEAD || $class == TAG) ? $class : _OPEN;
                $template = $this->menu->findOneBy(ID, $action);
                //Log::debug($this, "terms", print_r($terms,true));
                if (isset($template))
                    foreach ($terms as $term) {
                        $label = $this->getLabel($term->name, $term->term_value);
                        $page = $this->createPage(TERM, $template, $term, $label);
                        $page->setActive($this->isActive($term->id));
                        $term_menu->addPage($page);
                    }
            }
        }
        $this->html = null;
        if (count($term_menu) > 0) {
            $this->html = $this->view->navigation()->menu()->renderMenu($term_menu, $this->menuOptions);
        }
        //Log::debug($this, "node", $this->html);
        unset($term_menu);
        return $this;
    }


    private function isActive($term_id)
    {
        if (!Zend_Registry::isRegistered(TERMS))
            return false;
        $current = Zend_Registry::get(TERMS);
        return in_array($term_id, $current);
    }


    private function getLabel($term_name, $term_value)
    {
        $label = $term_name;
        if (!empty($term_value)) {
            $label .= is_numeric($term_value) ? ' [' . $term_value . ']' : ': ' . $term_value;
        }
        return $label;
    }

}
