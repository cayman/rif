<?php
class Site_Form_Filter_Title implements Zend_Filter_Interface
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
        $title=trim($str[0]);
        Log::debug($this,"filter ($value1) to ($title)");
        return $title;
    }
}