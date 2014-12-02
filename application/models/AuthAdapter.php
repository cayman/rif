<?php
class Site_Model_AuthAdapter extends Zend_Auth_Adapter_DbTable
{
    protected $_tableName = 'user';
    protected $_identityColumn = 'login';
    protected $_credentialColumn = 'password';
	//protected $_credentialTreatment = 'PASSWORD(?)';//'MD5(?)';

    public function __construct($login, $password)
    {
        $this->_zendDb = Zend_Db_Table::getDefaultAdapter();
        $this->_identity = $login;
        $this->_credential = $password;
    }

    public function getUser()
    {
        $row=$this->getResultRowObject(array('id','login','name','role'));
        Log::debug($this,'getUser',$row);
        return new Site_Model_User($row);
    }

}