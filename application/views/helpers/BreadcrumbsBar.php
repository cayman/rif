<?php
/**
 * User: Rustem
 * Date: 25.04.2010
 * Time: 13:57:29
 */

class Site_View_Helper_BreadcrumbsBar extends Zend_View_Helper_Navigation_Breadcrumbs{

    /**
     * Returns an HTML string containing an 'a' element for the given page
     *
     * @param  Zend_Navigation_Page $page  page to generate HTML for
     * @return string                      HTML string for the given page
     */
    public function htmlify(Zend_Navigation_Page $page)
    {
        // get label and title for translating
        $label = $page->getLabel();
        $title = $page->getTitle();

        if ($this->getUseTranslator() && $t = $this->getTranslator()) {
            if (is_string($label) && !empty($label)) {
                $label = $t->translate($label);
            }
            if (is_string($title) && !empty($title)) {
                $title = $t->translate($title);
            }
        }
        if(isset($page->link) and ($page->link===false))
            return $this->view->escape($label);
        // get attribs for anchor element
        $attribs = array(
            'id'     => $page->getId(),
            'title'  => $title,
            'class'  => $page->getClass(),
            'href'   => $page->getHref(),
            'target' => $page->getTarget()
        );

        return '<a' . $this->_htmlAttribs($attribs) . '>'
             . $this->view->escape($label)
             . '</a>';
    }

    /**
     * View helper entry point:
     * Retrieves helper and optionally sets container to operate on
     *
     * @param  Zend_Navigation_Container $container     [optional] container to
     *                                                  operate on
     * @return Zend_View_Helper_Navigation_Breadcrumbs  fluent interface,
     *                                                  returns self
     */
    public function BreadcrumbsBar(Zend_Navigation_Container $container = null){
        return parent::breadcrumbs($container);
    }


}
