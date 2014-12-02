<?php
class Site_View_Helper_ItemLink extends Site_View_Helper_Html {

    private $page;

    public function itemLink(Zend_Navigation_Page $page, $label = null,$href=null) {
        $this->html = null;
        $this->page = $page;
        if ($page) {
            if ($label == null)
                $label = $this->view->translate($page->label);
            if ($href == null)
                $href = $page->href;
            $this->html = $this->view->htmlTag('a', $label, array('href' => $href,
                                               TITLE => $page->title, 'style' => $page->style));
        }
        return $this;
    }

    public function checkbox() {
        if ($this->html) {
            $attribs = ($this->page->active) ? array('checked' => true) : null;
            $checkbox = $this->page->checkbox;
            $this->html = $this->view->formCheckbox($checkbox[NAME],$checkbox[VALUE], $attribs) . $this->html;
        }
        return $this;
    }

    public function li($class = null,$close = true) {
        if(isset($this->html)){
            if ($this->page->active)
                $class = isset($class) ? $class . ' active' : 'active';
            $this->html = $this->view->htmlTag('li', $this->html, array('class'=>$class),$close);
        }
        return $this;
    }

}
