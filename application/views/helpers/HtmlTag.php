<?php
/**
 * User: Rustem
 * Date: 03.04.2010
 * Time: 1:35:17
 */

class Site_View_Helper_HtmlTag extends Zend_View_Helper_HtmlElement{

    /**Формирование тега */
     public function htmltag($tag, $content, array $attribs = array(),$close=true)
     {
         $attribs = array_filter($attribs);
         $xhtml = '<'.$tag . $this->_htmlAttribs($attribs);
         if(isset($content)){
             $xhtml.= '>' .$content;
             if($close)  $xhtml .='</'.$tag.'>';
             $xhtml .= self::EOL;
         }

         else
             $xhtml.= '/>'.self::EOL;

         return $xhtml;
     }


}
