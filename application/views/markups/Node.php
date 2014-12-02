<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zakirov
 * Date: 02.04.2010
 * Time: 15:48:36
 * To change this template use File | Settings | File Templates.
 */

class Site_View_Markup_Node implements Zend_Markup_Renderer_TokenConverterInterface {


    public function convert(Zend_Markup_Token $token, $text) {

        if (trim($text)=='+') {
            $id = (int) $token->getAttribute(NODE);
            $node = Site_Model_Node::getMapper()->find($id);
            if (isset($node)){
                $bbcode=Site_View_Markup_BBCode::factory();
                $ret_value = '<div class="sub">'.$bbcode->render($node->text).'</div>'.PHP_EOL;
            }
            else
                $ret_value = "<b>!Неверная ссылка:node=".$token->getAttribute(NODE)."</b>";
        }
        else
            $ret_value = $this->getNodeLink($token, $text);
        Log::debug($this,$ret_value);
        return $ret_value;
    }

    private function getNodeLink($token, $text) {
        return "<a href=\"/node/{$token->getAttribute(NODE)}\">{$text}</a>";
    }
}
