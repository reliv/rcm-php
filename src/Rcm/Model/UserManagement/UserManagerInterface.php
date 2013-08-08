<?php

namespace Rcm\Model\UserManagement;

use Rcm\Entity\User;

interface UserManagerInterface {

    /**
     * @return \Rcm\Entity\AdminPermissions | null
     */
    public function getLoggedInAdminPermissions();

    /**
     * @return \Rcm\Entity\User | null
     */
    public function getLoggedInUser();

    /**
     * @param string $username user name
     * @param string $password password
     *
     * @return \Rcm\Entity\User | null
     */
    public function loginUser($username, $password);

    public function isCurrentUser($username);

    public function logoutUser();

    public function saveUser(User $user);

}