<?php
class Site_Form_Element_CommentCaptcha extends Zend_Form_Element_Captcha{

    /**
     * Constructor
     *
     * $spec : name of element
     * @param  string $name: name of element
     * @return void
     */
    public function __construct($name='captcha'){
        parent::__construct($name, array(
            'label' => 'captcha',
            'captcha' => array(
                'captcha'   => 'Image', // Тип CAPTCHA
                'wordLen'   => 4,       // Количество генерируемых символов
                'fontSize'   => 28,
                'width'     => 150,     // Ширина изображения
                'height'     => 50,     // Ширина изображения
                'timeout'   => 3600,     // Время жизни сессии хранящей символы
                'expiration'=> 3600,     // Время жизни изображения в файловой системе
                'font'      => APPLICATION_PATH.'/../font/arial.ttf', // Путь к шрифту
                'imgDir'    => PUBLIC_PATH.'/captcha/', // Путь к изобр.
                'imgUrl'    => '/captcha/', // Адрес папки с изображениями
                'gcFreq'    => 5        // Частота вызова сборщика мусора
            )));
       /*$this->addDecorator('HtmlTag',array('tag'=>'p'))
            ->addDecorator('Label',null);*/
    }
}