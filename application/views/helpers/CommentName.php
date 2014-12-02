<?php
class Site_View_Helper_CommentName extends Site_View_Helper_Html {

    private $node=null;

    public function commentName($comment) {
        $this->html=$this->view->escape($comment->name).PHP_EOL;
        return $this;
    }

}
