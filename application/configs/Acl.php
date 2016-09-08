<?php
class Site_Configs_Acl extends Zend_Acl {

    private function initRoles() {
        // Создание новой роли guest («гость»)
        $this->addRole(new Zend_Acl_Role(ADMIN));
        $this->addRole(new Zend_Acl_Role(GUEST));
        $this->addRole(new Zend_Acl_Role(READER),GUEST);
        // Создание роли editor («редактор») которая наследует  роль guest
        $this->addRole(new Zend_Acl_Role(EDITOR), READER);
    }

    private function initResources() {
        // Создаём ресурс «Главная страница»
        $this->addResource(new Zend_Acl_Resource(MAIN));

        //  Создаём ресурс «Аутефикация»
       $this->addResource(new Zend_Acl_Resource(AUTH));

        //  Создаём ресурс «Классификатор»
       $this->addResource(new Zend_Acl_Resource(CLASSIFIER));

        //  Создаём ресурс «Аутефикация»
       $this->addResource(new Zend_Acl_Resource(USER));

        //  Создаём ресурс «Тип материала»
       $this->addResource(new Zend_Acl_Resource(TYPE));

        //  Создаём ресурс «Словарь» который наследует ресурс «Главная страница»
       $this->addResource(new Zend_Acl_Resource(TERM),MAIN);

       //  Создаём ресурс «Поиск» который наследует ресурс «Главы»

       $this->addResource(new Zend_Acl_Resource(NODE),MAIN);

        //  Создаём ресурс «Комментарий» который наследует ресурс «Узел»
       $this->addResource(new Zend_Acl_Resource(COMMENT), NODE);

        //  Создаём ресурс «Сервис для админа»
       $this->addResource(new Zend_Acl_Resource('service'));

        Log::debug($this," initControllerResources");

         //  Создаём ресурс «Блоки только для админа»
       $this->addResource(new Zend_Acl_Resource(MENU));

       $this->addResource(new Zend_Acl_Resource(BLOCK));


        Log::debug($this," initBlockResources");
    }

    private function initPrivileges() {
        // Разрешаем гостю просматривать страницы

        // Разрешаем администратору все
        $this->allow(ADMIN);
        $this->deny(ADMIN, AUTH, _LOGIN);        

        $this->allow(GUEST, AUTH, _LOGIN);
        $this->allow(GUEST, MAIN,  array(_OPEN,'feed','map')); //index и всех наследников:head, rank, comments, verse,author
        $this->allow(GUEST, CLASSIFIER , array(_OPEN));
        $this->allow(GUEST, TERM , array(SEARCH));
        $this->allow(GUEST, NODE, array(AUTHOR, VERSE, RANK, TERM, KEY,COMMENTS)); //@todo ??????????????????????
        $this->allow(GUEST, COMMENT, _LIST);
        $this->allow(GUEST, COMMENT, _ADD, new Site_Service_CleanIP());
        $this->allow(GUEST, BLOCK);
        $this->deny(GUEST, BLOCK,array('phpinfo'));
        $this->allow(GUEST, MENU);
        $this->deny(GUEST, MENU,array(TAXONOMY,SORT,USER));

        // работа с классифиватором + права GUEST
        $this->deny(READER, AUTH, _LOGIN);
        $this->allow(READER, AUTH, _LOGOUT);

        // Разрешаем редактору добавлять и редактировать стихи. + права READER
        $this->allow(EDITOR, TERM , array(_OPEN,_PRINT,_EDIT,_ADD));
        $this->allow(EDITOR, NODE, array(_ADD, _EDIT,_COPY));
        $this->deny(EDITOR, NODE, array(RANK));
        $this->allow(EDITOR, COMMENT, array(_EDIT, _DELETE));
        $this->allow(EDITOR, 'service', 'clean');
        $this->allow(EDITOR, CLASSIFIER ,array(_OPEN, _LIST, _ADD,_EDIT,_DELETE));
        $this->allow(EDITOR, MENU,array(TAXONOMY,SORT));

        Log::debug($this,"initPrivileges");
    }


    public function __construct() {
        $this->initRoles();
        $this->initResources();
        $this->initPrivileges();
        Zend_Registry::set(SITE_ACL, $this);
        Log::debug($this,"ACL constructed");        
    }
}
