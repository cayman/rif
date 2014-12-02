<?php
class Site_View_Helper_Letter extends Zend_View_Helper_Abstract{

    public function letter($text) {
        $letter=mb_substr($text,0,1);
        $after=preg_replace("/\n/","<br/>",mb_substr($text,1,mb_strlen($text)));
        return '<span class="letter">'.$letter."</span>".$after;
    }
}
