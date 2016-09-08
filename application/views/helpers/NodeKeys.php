<?php
class Site_View_Helper_NodeKeys extends Site_View_Helper_Html {

    private $menuOptions = array('indent' => 4, 'ulClass' => 'links inline');
    
    public function nodeKeys($node) {
        $key_menu=new Zend_Navigation();
        $words = explode(',', str_replace(' ', ',', $node->key));
        $words = array_filter($words);
        //if ($this->view->login()->isAllowed(NODE, KEY) && count($words)>0) {
            $i=1;
            foreach ($words as $word) {
                $id='node'.$node->id.'-'.KEY.$i++;
                $page = $this->createPage1($id,$word);
                $key_menu->addPage($page);
            }
        //}
        $this->html = $this->view->navigation()->menuBar()->renderMenu($key_menu, $this->menuOptions);
       //Log::debug($this, "node", $this->html);
        unset($key_menu);
        return $this;
    }

    protected function createPage1($id,$word) {
        $word_lower= mb_strtolower($word);
        $type=$this->getActive($word_lower);
        switch ($type){
            case 1: $label="<b>$word</b>"; break;
            case 2: $label="<i>$word</i>"; break;
            default: $label=$word;          
        }
        return new Zend_Navigation_Page_Mvc(
            array(ID=>$id,
                  LABEL=>$label,
                  TITLE=>$word,
                  PARAMS=>array(ID =>$word_lower),
                  ACTIVE=>($type!==false),
                  ROUTE=>SEARCH  
            )
        );
    }


    private function getActive($word) {
        if (Zend_Registry::isRegistered(SEARCH)) {
            $search = Zend_Registry::get(SEARCH);
            if($search == $word) return 1;
            $pos=strpos($word, $search);
            if($pos>0) return 2;
            else return $pos;

        }
        else return false;
    }



}
