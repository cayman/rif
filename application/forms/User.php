<?php
class Site_Form_User extends Zend_Form {

    public function __construct($action) {
        Log::info($this, 'actionUrl', $action);
        parent::__construct();
        $this->addElementPrefixPath('Site_Form_Filter', APPLICATION_PATH . '/forms/Filter', 'filter');
        $this->addElementPrefixPath('Site_Form_Decorator', APPLICATION_PATH . '/forms/Decorator', 'decorator');
        $this->setAction($action);
    }

    public function init() {
        $this->setMethod('post')
        ->setAttrib('class', 'edit_form')
        ->setAttrib(ID, 'user_form');


        $name = new Site_Form_Element_CommonName(NAME);
        $login = new Site_Form_Element_CommonName(CODE);
        $password = new Site_Form_Element_CommonName(DESC);
        $role = new Site_Form_Element_CommonSelect(ROLE,Site_Service_Registry::get(USER)->getRolesOptions());


        $page = new Site_Form_Element_CommonHidden(PAGE);

        $submit = new Site_Form_Element_Submit();
        $this->addElement($name)
        ->addElement($login)
        ->addElement($password)
        ->addElement($role)
        ->addElement($submit)
        ->addElement($page);


    }

    public function copy(Site_Model_User $user) {
        $id = $this->getValue(ID);
        if (isset($id) && $id > 0) $user->id = $id;
        $user->name = $this->getValue(NAME);
        $user->login = $this->getValue(LOGIN);
        $user->role = $this->getValue(ROLE);

    }




    public function populate(Site_Model_User $user) {
        $this->setDefaults(array(
            ID => $user->id,
            NAME => $user->name,
            LOGIN => $user->login,
            ROLE => $user->role,
        ));
    }
}
