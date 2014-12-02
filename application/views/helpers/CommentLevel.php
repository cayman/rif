<?php
class Site_View_Helper_CommentLevel extends Site_View_Helper_Html {


    public function commentLevel($comment) {
      $this->html=null;
      if($this->view->login()->hasIdentity() && $comment->level){
            $this->html= $comment->level_name;
            //$this->html= $comment->level->name;
      }
      return $this;
    }

}
