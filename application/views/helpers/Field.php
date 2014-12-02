<?php
class Site_View_Helper_Field extends Site_View_Helper_Html {


    public function field($value,$route=null) {
        if($value==null) $value='&nbsp;';
         $this->html=$value.PHP_EOL;
        return $this;
    }
}
