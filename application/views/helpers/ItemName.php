<?php
class Site_View_Helper_ItemName extends Site_View_Helper_Html {


    public function itemName($entity,$route=null) {
        if(isset($route))
        $this->html = $this->view->htmlTag('a', $entity->name,
            array('href' => $this->getUrl($entity->id,$route),
                   TITLE => isset($entity->desc)?$entity->desc:$entity->name)
        );
        else
          $this->html=preg_replace("/\n/","<br/>",$this->view->escape($entity->name)).PHP_EOL;   
        return $this;
    }

    protected function getUrl($id,$route) {
        return Zend_Controller_Front::getInstance()->getRouter()->assemble(array(ID => $id,ACTION=>_OPEN), $route ,false);
    }

}
