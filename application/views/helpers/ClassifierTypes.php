<?php
class Site_View_Helper_ClassifierTypes extends Site_View_Helper_Html {

    public function classifierTypes(Zend_Db_Table_Row_Abstract $classifier) {
        $this->html = null;
        foreach ($classifier->getTypesClassifier() as $row) {
            $this->html .= (isset($this->html)) ? ', ' . $row->getType()->name : $row->getType()->name;
        }
        return $this;
    }
}
