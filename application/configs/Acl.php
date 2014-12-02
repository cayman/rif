<?php
class Site_Configs_Acl extends Zend_Acl {

    private function initRoles() {
        // �������� ����� ���� guest (�������)
        $this->addRole(new Zend_Acl_Role(ADMIN));
        $this->addRole(new Zend_Acl_Role(GUEST));
        $this->addRole(new Zend_Acl_Role(READER),GUEST);
        // �������� ���� editor (���������) ������� ���������  ���� guest
        $this->addRole(new Zend_Acl_Role(EDITOR), READER);
    }

    private function initResources() {
        // ������ ������ �������� ��������
        $this->addResource(new Zend_Acl_Resource(MAIN));

        //  ������ ������ �������������
       $this->addResource(new Zend_Acl_Resource(AUTH));

        //  ������ ������ ��������������
       $this->addResource(new Zend_Acl_Resource(CLASSIFIER));

        //  ������ ������ �������������
       $this->addResource(new Zend_Acl_Resource(USER));

        //  ������ ������ ���� ���������
       $this->addResource(new Zend_Acl_Resource(TYPE));

        //  ������ ������ ��������� ������� ��������� ������ �������� ��������
       $this->addResource(new Zend_Acl_Resource(TERM),MAIN);

       //  ������ ������ ������ ������� ��������� ������ �������

       $this->addResource(new Zend_Acl_Resource(NODE),MAIN);

        //  ������ ������ ������������ ������� ��������� ������ �����
       $this->addResource(new Zend_Acl_Resource(COMMENT), NODE);

        //  ������ ������ ������� ��� ������
       $this->addResource(new Zend_Acl_Resource('service'));

        Log::debug($this," initControllerResources");

         //  ������ ������ ������ ������ ��� ������
       $this->addResource(new Zend_Acl_Resource(MENU));

       $this->addResource(new Zend_Acl_Resource(BLOCK));


        Log::debug($this," initBlockResources");
    }

    private function initPrivileges() {
        // ��������� ����� ������������� ��������

        // ��������� �������������� ���
        $this->allow(ADMIN);
        $this->deny(ADMIN, AUTH, _LOGIN);        

        $this->allow(GUEST, AUTH, _LOGIN);
        $this->allow(GUEST, MAIN,  array(_OPEN,'feed','map')); //index � ���� �����������:head, rank, comments, verse,author
        $this->allow(GUEST, CLASSIFIER , array(_OPEN));
        $this->allow(GUEST, TERM , array(SEARCH));
        $this->allow(GUEST, NODE, array(AUTHOR, VERSE, RANK, TERM, KEY,COMMENTS)); //@todo ??????????????????????
        $this->allow(GUEST, COMMENT, _LIST);
        $this->allow(GUEST, COMMENT, _ADD, new Site_Service_CleanIP());
        $this->allow(GUEST, BLOCK);
        $this->deny(GUEST, BLOCK,array('phpinfo'));
        $this->allow(GUEST, MENU);
        $this->deny(GUEST, MENU,array(TAXONOMY,SORT,USER));

        // ������ � ��������������� + ����� GUEST
        $this->deny(READER, AUTH, _LOGIN);
        $this->allow(READER, AUTH, _LOGOUT);

        // ��������� ��������� ��������� � ������������� �����. + ����� READER
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
