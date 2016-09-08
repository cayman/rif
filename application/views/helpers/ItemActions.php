<?php
class Site_View_Helper_ItemActions extends Site_View_Helper_Html
{
    private $menuOptions = array('indent' => 4, 'ulClass' => 'links inline');
    private $menu;
    private $name;
    private $items;


    public function itemActions($name, $entities)
    {
        $navigation = Zend_Registry::get(NAVIGATION);
        $this->name = $name;
        $this->menu = $navigation[$name];
        $this->setItems($entities);
        return $this;
    }

    protected function setItems($items)
    {
        if (is_object($items))
            $this->items = array('item' => $items);
        else if (is_array($items) && isset($items['item']))
            $this->items = $items;
        else
            throw new Zend_Exception("Unknown entity");
    }

    protected function getCurrentItem($page)
    {
        if (isset($page->item) && isset($this->items[$page->item]))
            return $this->items[$page->item];
        else
            return $this->items['item'];
    }

    /**
     * Прорисовка группы ссылок
     * по шаблонам Nabigation_Page
     * @param  $ids
     * @param bool $hideLabel
     * @return Site_View_Helper_ItemActions
     */
    public function part($ids, $hideLabel = false)
    {
        if (is_string($ids)) $ids = array($ids);
        $this->html=null;
        $menu = new Zend_Navigation();
        foreach ($ids as $id) {
            $template = $this->menu->findOneBy(ID, $id);
            if (isset($template)) {
                $item = $this->getCurrentItem($template);
                $page= $this->createPage($this->name,$template,$item,$hideLabel?"":null);
                $menu->addPage($page);
            }
        }
        $this->html = $this->view->navigation()->menuBar()->renderMenu($menu, $this->menuOptions);
        unset($menu);
        return $this;
    }

    /**
     * Прорисовка одной ссылки
     * по шаблону Nabigation_Page
     * @param  $id
     * @return Site_View_Helper_ItemActions
     */
    public function item($id)
    {
        $this->html=null;
        $template = $this->menu->findOneBy(ID, $id);
        if (isset($template)) {
            $item = $this->getCurrentItem($template);
            $page= $this->createPage($this->name,$template,$item);
            $this->html = $this->view->navigation()->menuBar()->htmlify($page);
        }
        return $this;
    }

    /**
     * Прорисовка одной ссылки
     * по шаблону Nabigation_Page
     * @param  $id
     * @return Site_View_Helper_ItemActions
     */
    public function href($id)
    {
        $href=null;
        $template = $this->menu->findOneBy(ID, $id);
        if (isset($template)) {
            $item = $this->getCurrentItem($template);
            $page= $this->createPage($this->name,$template,$item);
            $href=$page->getHref();
        }
        return $href;
    }


}
