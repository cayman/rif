<?php
class Site_Form_Filter_Description implements Zend_Filter_Interface
{

    /**
     * Производит фильтрацию в соответствии с назначением фильтра
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        $value1 =$value;
        $str=explode("\n",$value);
        $desc=trim($str[0]).' '.trim($str[1]);
        Log::debug($this,"filter ($value1) to ($desc)");
        return $desc;
    }
}