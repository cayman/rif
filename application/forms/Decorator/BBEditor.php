<?php
Class Site_Form_Decorator_BBEditor extends Zend_Form_Decorator_Abstract {


    private function buttons() {
        return
        '<div class="btn bold"></div>
        <div class="btn italic"></div>
        <div class="btn underline"></div>
        <div class="sbtn colon">:</div>
        <div class="sbtn semi">;</div>
        <div class="sbtn dquote">«»</div>
        <div class="sbtn ellipsis">…</div>          
        <div class="btn link"></div>
        <div class="btn quote"></div>
        <div class="btn code"></div>
        <div class="btn image"></div>
        <div class="btn usize"></div>
        <div class="btn dsize"></div>
        <div class="btn nlist"></div>
        <div class="btn blist"></div>
        <div class="btn litem"></div>
        <div class="btn back"></div>
        <div class="btn forward"></div>';
    }

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

        $view->jQuery()->addJavascript("
        \$j(function(){
            \$j('textarea[name={$element->getName()}]').bbcodeeditor(
            {
                bold:\$j('.bold'),italic:\$j('.italic'),underline:\$j('.underline'),
                colon:\$j('.colon'),semi:\$j('.semi'),dquote:\$j('.dquote'),ellipsis:\$j('.ellipsis'),
                link:\$j('.link'),quote:\$j('.quote'),
                code:\$j('.code'),image:\$j('.image'),
                usize:\$j('.usize'),dsize:\$j('.dsize'),nlist:\$j('.nlist'),blist:\$j('.blist'),litem:\$j('.item'),
                back:\$j('.back'),forward:\$j('.forward'),
                back_disable:'btn back_disable',
                forward_disable:'btn forward_disable',
                exit_warning:false /*,preview:\$j('.preview')*/
                });
            });
        ");
        return $this->buttons().PHP_EOL."<div class=\"bbtext\">$content</div><div class=\"preview\"></div>";

    }

}