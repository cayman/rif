<?php
class Site_View_Helper_CommentText extends Site_View_Helper_Html {

    private $comment=null;

    public function commentText($comment) {
        $this->comment=$comment;
        $text=$comment->text;
       // $this->html=preg_replace("/\n/","<br/>",$this->view->escape($this->comment->text)).PHP_EOL;

        $bbcode=Site_View_Markup_BBCode::factory($this->view->Login()->getRole());
        $text=$bbcode->render($text);
        $this->html = $text . PHP_EOL;

        return $this;
    }

}
