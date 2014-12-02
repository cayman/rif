<?php
class Site_View_Helper_ItemPic extends Site_View_Helper_Html {

    public function itemPic($entity) {
         $this->html=null;
         if(!empty($entity->pic))
            $this->html = $this->view->htmlTag('img', null,
                array('src' => '/pic/' . $entity->pic, TITLE => $entity->desc, 'border' => 0));
        return $this;
    }

}
