<?php
class Site_View_Helper_ItemTitle extends Site_View_Helper_Html {


    public function itemTitle($entity) {
        $this->html=preg_replace("/\n/","<br/>",$this->view->escape($entity->title)).PHP_EOL;
        return $this;
    }

}
