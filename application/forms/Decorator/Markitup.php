<?php
Class Site_Form_Decorator_Markitup extends Zend_Form_Decorator_Abstract {


    /**
     * Рендеринг декоратора
     *
     * @param string $content
     * @return string
     */
    public function render($content) {
        // Получаем объект элемента к которому применяется декоратор
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element) {
            return $content;
        }
        // Проверяем объект вида зарегистрированного для формы
        if (null === $element->getView()) {
            return $content;
        }
        $view = $element->getView();
        $id=$this->getElement()->getId();
        $view->jQuery()->addOnLoad('$j("#bbcode").markItUp(myBbcodeSettings);');
        return $content;

    }

}