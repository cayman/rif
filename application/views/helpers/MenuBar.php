<?php
/**
 * User: Rustem
 * Date: 25.04.2010
 * Time: 13:57:29
 */

class Site_View_Helper_MenuBar extends Zend_View_Helper_Navigation_Menu {

    /**
     * Расширяем метод htmlify
     *
     * Returns an HTML string containing an 'a' element for the given page if
     * the page's href is not empty, and a 'span' element if it is empty
     *
     * Overrides {@link Zend_View_Helper_Navigation_Abstract::htmlify()}.
     *
     * @param  Zend_Navigation_Page $page  page to generate HTML for
     * @return string                      HTML string for the given page
     */
    public function htmlify(Zend_Navigation_Page $page) {
        // get label and title for translating
        $label = $page->getLabel();
        $title = $page->getTitle();

        // translate label and title?
        if ($this->getUseTranslator() && $t = $this->getTranslator()) {
            if (is_string($label) && !empty($label)) {
                $label = $t->translate($label);
            }
            if (is_string($title) && !empty($title)) {
                $title = $t->translate($title);
            }
        }

        // get attribs for element
        $attribs = array(
            'id' => $page->getId(),
            'title' => $title,
            'class' => $page->getClass(),
        );

        // does page have a href?
        if ($href = $page->getHref()) {
            $element = 'a';
            $params=$page->getParams();
            if(isset($page->ajax)){
                $target=$page->getTarget();
                $params=$page->getParams();
                $title=$this->view->translate($page->title);
                $attribs['href'] = "javascript:void(0)";
                $attribs['onClick']=sprintf($page->ajax,$href,$title,$params[ID]);

            }else if(isset($params["script"])){
                $attribs['href'] = "javascript:void(0)";
                $attribs['onClick'] = "document.location.href='$href'";
            }else{
                $attribs['href'] = $href;
            }
        } else {
            $element = 'span';
        }

        $html = '<' . $element . $this->_htmlAttribs($attribs) . '>'.$label.'</' . $element . '>';
        //Log::debug($this, $href, $html);
        return $html;
    }


    /**
     * View helper entry point:
     * Retrieves helper and optionally sets container to operate on
     *
     * @param  Zend_Navigation_Container $container  [optional] container to
     *                                               operate on
     * @return Zend_View_Helper_Navigation_Menu      fluent interface,
     *                                               returns self
     */
    public function menuBar(Zend_Navigation_Container $container = null) {
        return parent::menu($container);
    }


}
