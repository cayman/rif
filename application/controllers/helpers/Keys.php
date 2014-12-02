<?php
class Site_Controller_Helper_Keys extends Zend_Controller_Action_Helper_Abstract
{

    public function parse($value,$limit=null)
    {
        $keywords    =array();
        $words=array_map('trim',explode(',',str_replace(' ',',',$value)));
        foreach($words as $word)
            if (strlen($word)>0)
               $keywords[]= trim($word);
        if (isset($limit))
            $keywords = array_slice($keywords, 0, $limit);
        log::debug($this,'parse',$keywords);
        return $keywords;
    }


    /**
     * Паттерн Стратегии: вызываем помощник как метод брокера
     *
     * @param  string $name
     * @param  array|Zend_Config $options
     * @return Zend_Form
     */
    public function direct($value,$limit=null)
    {
        return $this->parse($value,$limit=null);
    }
}