<?php
class Site_View_Helper_Html extends Zend_View_Helper_Abstract {

    protected $html = null;

    public function html($text) {
        $this->html = $text;
        return $this;
    }

    public function label($name) {
        if ($this->html) {
            $this->html = $this->view->translate($name) . ': ' . $this->html;
        }
        return $this;
    }

    /**Проверка прав на действие*/
    public function allowed($resource, $privilege) {
        if (!$this->view->login()->isAllowed($resource, $privilege))
            $this->html = "";
        return $this;
    }

    /**Проверка прав на действие*/
    public function visible($visible=true) {
        if ($visible==false)
            $this->html = "";
        return $this;
    }


    protected static $tags = array('div', 'p', 'h1', 'h2', 'h3', 'h4', 'span','a','li');

    /**Отображение тега */
    public function __call($name, $arguments) {
        if (in_array($name, self::$tags)) {
            if (isset($this->html)) {
                if(isset($arguments[1]) && is_array($arguments[1]))
                    $params = $arguments[1];
                else
                    $params = array();
                if (isset($arguments[0]))  $params['class'] = $arguments[0];
                $this->html = $this->view->htmlTag($name, $this->html, $params);
            }
            return $this;
        } else
            throw new Exception("Method $name not found");
    }

    /**
     * Создания Zend_Navigation_Page на основе шаблона
     *
     * @param  $name
     * @param Zend_Navigation_Page_Mvc $template
     * @param  $item
     * @return Zend_Navigation_Page_Mvc
     */
    protected function createPage($name,Zend_Navigation_Page_Mvc $template, $item,$label=null)
    {
        if(empty($template)||empty($name)||empty($item))
            throw new Zend_Exception("Parameters for navigation page not set",8001);
        $page = clone $template;
        $page->setId($name . $item->id . '-' . $page->id);
        if (isset($label)) $page->setLabel($label);
        else if(empty($page->label)) $page->setLabel(isset($item->name)?$item->name:$item->title);
        if (empty($page->title)) $page->setTitle($item->title);
        $params = $page->getParams();
        $params[ID] = isset($params[ID]) ? $item->__get($params[ID]) : $item->id;
        $page->setParams($params);
        return $page;
    }


    public function __toString() {
        if(isset($this->html))
            return $this->html;
        else return '';
    }
}


