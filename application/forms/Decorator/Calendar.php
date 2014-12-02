<?php
Class Site_Form_Decorator_Calendar extends Zend_Form_Decorator_Abstract
{
    /**
     * ��������� ����� ����������� Javacript � CSS ��� ���������
     * ����������� ���������� $jsAndCss �������� �� ��, ����� �����������
     * �������������� ������ ���� ���
     *
     * @return string
     */
   /* private function _getJsAndCss()
    {
        static $jsAndCss = null;

        if($jsAndCss === null) {
           $jsAndCss = '
   			<link rel="stylesheet" type="text/css" href="/calendar/css/jscal2.css" />
    		<link rel="stylesheet" type="text/css" href="/calendar/css/border-radius.css" />
    		<link rel="stylesheet" type="text/css" href="/calendar/css/gold/gold.css" />
    		<script type="text/javascript" src="/calendar/js/jscal2.js"></script>
    		<script type="text/javascript" src="/calendar/js/lang/ru.js"></script>
            ';
            return $jsAndCss;
        }
        return '';
    }*/


    /**
     * ��������� ���� ������ � ����������� ��������. ��������� ���������
     *
     * @return string
     */
    private function _getCalendarLink()

    {   $element=$this->getElement();
        $value=$this->getElement()->getUnfilteredValue();
        if(isset($value) && Zend_Date::isDate($value, 'dd.MM.yyyy hh.mm.ss'))
            $date =new Zend_Date($value,'dd.MM.yyyy HH.mm.ss');
        else
            $date =new Zend_Date();
        Log::info($this,"selected date", $date);
        $calendarLink = '<button type="button" id="'. $element->getName().'-trigger" >...</button>
            <script type="text/javascript">//<![CDATA[
                Calendar.setup({
                    inputField  : "'.$element->getName().'",
                    trigger    : "'.$element->getName().'-trigger",
                    onSelect   : function() { this.hide() },
                    showTime    : true,
                    dateFormat    : "%d.%m.%Y %H:%M:%S",
                    min: 20060101,
                    time      : '.($date->toString('HHmm')).',
                    date      : '.($date->toString('yyyyMMdd')).'
                   // selection : ['.($date->toString('yyyyMMdd')).']
                 });
            //]]></script>
        ';
        return $calendarLink;
    }


    /**
     * ��������� ����������
     *
     * @param string $content
     * @return string
     */
    public function render($content)
    {
        // �������� ������ �������� � �������� ����������� ���������
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element) {
            return $content;
        }
        // ��������� ������ ���� ������������������� ��� �����
        if (null === $element->getView()) {
            return $content;
        }
        $view =  $element->getView();

        return $content . $this->_getCalendarLink();

    }
}