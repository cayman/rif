<?php
class Site_Form_Filter_Cleare implements Zend_Filter_Interface
{

    protected $_deleteSymbols = array("x27","x22","x60","\t","\r",
                                    '\\','\'','`',
                                    '{','}','<','>',
                                    '&','~','^','$','#');


    /**
     * Производит фильтрацию в соответствии с назначением фильтра
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        $value1 =trim($value);
        // Проходим по массиву и заменяем каждый символ фильтруемого значения
        foreach($this->_deleteSymbols as $letterVal) {
            $value = str_replace($letterVal,' ',$value);
        }
        while ( strpos($value,'  ')!==false )
        {
            $value = str_replace('  ',' ',$value);
        };
        Log::debug($this,"filter ($value1) to ($value)");
        return trim($value);
    }
}