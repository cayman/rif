<?php
class Site_View_Helper_Back extends Zend_View_Helper_Abstract{

    public function back() {
//        $session = new Zend_Session_Namespace(SITE_HISTORY);
       $action=Zend_Controller_Front::getInstance()->getRequest()->getActionName();
//        if($action==_OPEN || $action==_HEAD){
//            Log::info($this,"current={$session->current}");
//            $stack = new Stack($session->prev);
//            Log::info($this," prev=",$stack);
//            $url=$stack->top();
//        }
//        else
//            $url=$session->current;
        $url=$_SERVER['HTTP_REFERER'];
        return  '<a href="'.$url.'">'.$this->view->translate('back').'</a>'.PHP_EOL;

    }

}