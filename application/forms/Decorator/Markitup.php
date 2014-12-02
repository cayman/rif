<?php
Class Site_Form_Decorator_Markitup extends Zend_Form_Decorator_Abstract {


    /**
     * ��������� ����������
     *
     * @param string $content
     * @return string
     */
    public function render($content) {
        // �������� ������ �������� � �������� ����������� ���������
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element) {
            return $content;
        }
        // ��������� ������ ���� ������������������� ��� �����
        if (null === $element->getView()) {
            return $content;
        }
        $view = $element->getView();
        $id=$this->getElement()->getId();
        $view->jQuery()->addOnLoad('$j("#bbcode").markItUp(myBbcodeSettings);');
        return $content;

    }

}