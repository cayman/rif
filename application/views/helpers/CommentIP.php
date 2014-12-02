<?php
class Site_View_Helper_CommentIP extends Site_View_Helper_Html {


    public function commentIP($comment) {
      $this->html=null;
      if($this->view->login()->hasIdentity())        
        $this->html="[".$comment->ip."]";
        return $this;
    }

}
