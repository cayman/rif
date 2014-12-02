<?php
class Site_View_Helper_NodeText extends Site_View_Helper_Html {

    private $node = null;
    private $short = null;

    public function nodeText($node,$count=null) {
        $this->node = $node;
        $text=$node->text;
        if($this->short=(isset($count) && substr_count($text,"\n") > $count))
            $text=brief($text, $count);

        $bbcode=Site_View_Markup_BBCode::factory($this->view->Login()->getRole());
        $text=$bbcode->render($text);

        $this->html = $text . PHP_EOL;
        return $this;
    }

    public function isShort(){
        return $this->short;
    }


}
