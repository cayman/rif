<?php
class Site_View_Helper_ItemId extends Site_View_Helper_Html {


    public function itemId($entity) {
        $this->html=$entity->id."</b>&nbsp;&nbsp;";
        return $this;
    }

}
