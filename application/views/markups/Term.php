<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zakirov
 * Date: 02.04.2010
 * Time: 15:48:36
 * To change this template use File | Settings | File Templates.
 */

class Site_View_Markup_Term implements Zend_Markup_Renderer_TokenConverterInterface {
    public function convert(Zend_Markup_Token $token, $text) {
          return "<a href=\"/term/{$token->getAttribute(TERM)}\"><b>@</b>{$text}</a>";
    }
}
