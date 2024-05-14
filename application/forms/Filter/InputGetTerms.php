<?php
class Site_Form_Filter_InputGetTerms implements Zend_Filter_Interface
{

    /**
     * Производит фильтрацию в соответствии с назначением фильтра
     *
     * @param string $value
     * @return string
     */
    public function filter($param)
    {
        if(is_numeric($param))
            return ($param>0)? $param: null;
        else if(is_string($param)){
            return trim(urldecode($param));
        }else
           return null;
    }
}

