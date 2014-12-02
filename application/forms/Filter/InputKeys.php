<?php
class Site_Form_Filter_InputKeys implements Zend_Filter_Interface
{

    protected $_deleteSymbols = array("x27","x22","x60","\t","\r",
                                    '\\','\'','`','/',
                                    '[',']','{','}','<','>',
                                    '&','~','^','$','#');

    protected $decode=false;


    /**
     * Производит фильтрацию в соответствии с назначением фильтра
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        Log::debug($this,"filter($value) decode={$this->decode}");
        if(empty($value))
            return null;
        //$value=trim(($this->decode)?urldecode($value):$value);
        $value=trim($value);

        // Проходим по массиву и заменяем каждый символ фильтруемого значения
        foreach($this->_deleteSymbols as $letterVal) {
            $value = str_replace($letterVal,' ',$value);
        }
        while ( strpos($value,'  ')!==false )
        {
            $value = str_replace('  ',' ',$value);
        };

        Log::debug($this,"return filtered($value)");

        if(empty($value))
            return null;
        else
            return $value;
    }

    public function setDecode($decode=true)
    {
        $this->decode = $decode;
    }



}

