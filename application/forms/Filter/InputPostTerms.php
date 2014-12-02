<?php
class Site_Form_Filter_InputPostTerms implements Zend_Filter_Interface
{
    private $_count=0;
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
            $param = trim($param);
            return $param;
        }else if(is_array($param)){
            $terms=array_keys(array_filter($param,'is_positive_numeric'));
            if(($this->_count=count($terms))==0)
                return null;
            if($this->_count==1)
                return $terms[0];
            else
                return $terms;
        }
        else
           return null;
    }
}


function is_positive_numeric($value) {
    if (is_numeric($value) && $value >= 1)
        return $value;
    else null;
}