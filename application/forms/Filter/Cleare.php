<?php
class Site_Form_Filter_Cleare implements Zend_Filter_Interface
{

    protected $_deleteSymbols = array("x27","x22","x60","\t","\r",
                                    '\\','\'','`',
                                    '{','}','<','>',
                                    '&','~','^','$','#');


    /**
     * ���������� ���������� � ������������ � ����������� �������
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        $value1 =trim($value);
        // �������� �� ������� � �������� ������ ������ ������������ ��������
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