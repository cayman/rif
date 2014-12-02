<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 27.09.2010
 * Time: 14:27:06
 * To change this template use File | Settings | File Templates.
 */

class Site_Service_Access extends Site_Service_Abstract {

    private $_role;
    private $_auth;
    private $_legalClassifier;
    private $_legalTerms;

    protected function init() {
        $this->_auth = Zend_Auth::getInstance();
    }


    /**
     * Return type by ID
     * @param  string $role
     * @return boolean
     */
    public function is($role) {
        return ($this->getRole() == $role);
    }

    /**
     * Return role
     * @return string
     */
    public function getRole() {
        if ($this->_role === null) {
            $user = $this->getUser();
            $this->_role = isset($user) ? $user->role : GUEST;
        }
        return $this->_role;
    }

    /**
     * Return user
     * @return string
     */
    public function getUser() {
        try {
            if ($this->hasIdentity())
                return $this->_auth->getStorage()->read();
            else
                return null;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getUser', 2001, $e);
        }
    }


    /**
     * authenticate
     * @param  string $login
     * @param  string $password
     * @return User[]
     */
    public function authenticate($login, $password) {
        try {
            $authAdapter = new Site_Model_AuthAdapter($login, $password);
            $result = $this->_auth->authenticate($authAdapter);
            switch ($result->getCode()) {
                case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                    throw new Site_Service_Exception('User not found',$login, 2002);
                case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                    throw new Site_Service_Exception('Wrong password',null, 2003);
                case Zend_Auth_Result::SUCCESS:
                    $user = $authAdapter->getUser();
                    $this->_auth->getStorage()->write($user);
                    $authSession = new Zend_Session_Namespace('Zend_Auth');
                    $authSession->setExpirationSeconds(21600);
                    return $user;
                default:
                    $messages = $result->getMessages();
                    throw new Site_Service_Exception('Authentication failed',$messages[0], 2004);
            }
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'authenticate', 2005, $e);
        }
    }

    /**
     * logout
     * @return null
     */
    public function logout() {
        try {
            $this->_auth->getStorage()->clear();
            $this->_auth->clearIdentity();
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error","logout", 2006, $e);
        }
    }

    /**
     * logout
     * @return null
     */
    public function hasIdentity() {
        try{
            return $this->_auth->hasIdentity();
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'hasIdentity', 2007, $e);
        }
    }

    /**
     * Return type by ID
     * @param  int $id
     * @return int[]
     */
    public function getLegalClassifier() {
        try {
            if ($this->_legalClassifier===null && !$this->is(ADMIN))
                $this->_legalClassifier = $this->getClassifierMapper()->findByRole($this->getRole());
            return $this->_legalClassifier;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getLegalClassifier', 2008, $e);
        }
    }

    /**
     * Return type by ID
     * @param  int $id
     * @return int[]
     */
    public function getLegalTerms() {
        try {
            if ($this->_legalTerms === null && !$this->is(ADMIN))
                $this->_legalTerms = $this->getTermMapper()->findByRole($this->getRole());
            return $this->_legalTerms;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getLegalTerms', 2009, $e);
        }
    }

    /**
     * @return string
     */
    public function about() {
        return 'Access service';
    }


}
