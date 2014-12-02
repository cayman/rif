<?php
class Site_View_Helper_ItemDate extends Site_View_Helper_Html {


    public function itemDate($entity) {
        $date=new Zend_Date($entity->date);
        if($this->view->login()->hasIdentity())
            $this->html=$date->toString();
        else
            $this->html=$date->get(Zend_Date::DATE_LONG,'ru');
        return $this;
    }

}
