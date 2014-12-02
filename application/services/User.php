<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 27.09.2010
 * Time: 14:27:06
 * To change this template use File | Settings | File Templates.
 */

class Site_Service_User extends Site_Service_Abstract {


    /**
     *  Return roles
     *  @return String[]
    */
    public function getRolesOptions() {
        return array(
            ADMIN => 'Администратор',
            EDITOR => 'Редактор',
            READER => 'Читатель'
            );
    }

    /**
     * Return all users
     * @return Site_Model_User[]
     */
    public function getUsers() {
        try {
            $list = $this->getUserMapper()->findAll();
            return $list;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getUsers', 5001, $e);
        }
    }


    /**
     * Return user
     * @param  int $id
     * @return Site_Model_User
     */
    public function getUser($id) {
        try {
            $user = $this->getUserMapper()->find($id);
            if (empty($user)) throw new Site_Service_Exception('User not found',$id,5002);
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getUser($id)', 5003, $e);
        }
    }

    /**
     * Append new User
     * @param  Site_Model_User $user
     * @return int $id
     */
    public function addUser($user) {
        try {
            $id = $this->getUserMapper()->create($user);
            if (!is_numeric($id)) throw new Site_Service_Exception('User not saved',null, 5004);
            return $id;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'addUser',5005,$e);
        }
    }

    /**
     * Save change in User
     * @param  Site_Model_User $user
     * @return null
     */
    public function editUser($user) {
        try {
            if(!$this->getUserMapper()->modify($user))
               throw new Site_Service_Exception('User not saved',null, 5006);
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'editUser',5007,$e);
        }
    }


    /**
     * @return string
     */
    public function about() {
        return 'User service';
    }


}
