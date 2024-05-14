<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zakirov
 * Date: 02.04.2010
 * Time: 16:18:31
 * To change this template use File | Settings | File Templates.
 */

class Site_View_Markup_BBCode {
    private $bbcode;

    private static $instance = null;

    public static  function factory($role) {
        if (self::$instance===null)
            self::$instance = new self($role);
        return self::$instance->bbcode;
    }


    public function __construct($role) {
        //$options = array('encoding' => UTF, 'useDefaultTags' => true); @todo Кодировку ставить не надо
        $options = array('useDefaultTags' => true);
        $this->bbcode = Zend_Markup::factory('Bbcode', 'Html', $options);

        $this->bbcode->addMarkup('line',
        Zend_Markup_Renderer_RendererAbstract::TYPE_ALIAS,
            array('name' => 'hr')
        );

            $this->bbcode->addMarkup('todo',
            Zend_Markup_Renderer_RendererAbstract::TYPE_REPLACE,
                array(
                    'start' => '<span style="color: red; font-style: italic ;"><b>@Исправить: </b>',
                    'end' => '</span>',
                    'group' => 'inline'
                )
            );
            $this->bbcode->addMarkup('note',
            Zend_Markup_Renderer_RendererAbstract::TYPE_REPLACE,
                array(
                    'start' => '<span style="color: green;"><b>@Заметка: </b>',
                    'end' => '</span>',
                    'group' => 'inline'
                )
            );

            $this->bbcode->addMarkup('node',
            Zend_Markup_Renderer_RendererAbstract::TYPE_CALLBACK,
                array('callback' => new Site_View_Markup_Node(),
                    'group' => 'inline'
                )
            );

            $this->bbcode->addMarkup('term',
            Zend_Markup_Renderer_RendererAbstract::TYPE_CALLBACK,
                array('callback' => new Site_View_Markup_Term(),
                    'group' => 'inline'
                )
            );


    }
}