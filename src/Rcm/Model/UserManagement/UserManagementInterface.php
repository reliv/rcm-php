<?php

namespace Rcm\Model\UserManagement;

interface UserManagementInterface {

    /* proposed by rod
    public function getLoggedInPerson();
    public function login($username,$password);
    public function newPerson()
    */

    /* old
    public function loginUser($accountNum, $password);
    public function saveUser(\RcmLogin\Entity\User $user);
    public function getNewUserInstance();
    public function encryptUserPassword($password);
    */
}