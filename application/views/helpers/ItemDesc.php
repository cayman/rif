<?php
class Site_View_Helper_ItemDesc extends Site_View_Helper_Html{

    public function itemDesc($entity) {
        $this->html=$this->view->escape($entity->desc).PHP_EOL;
        return $this;
    }

}
