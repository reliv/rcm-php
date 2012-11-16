<?php

namespace Rcm\Model\UserManagement;

use \Zend\Session\Container,
    \Rcm\Entity\AdminPermissions,
    \Rcm\Entity\User;

class DoctrineUserManager extends \Rcm\Model\EntityMgrAware
    implements UserManagerInterface
{
    protected $session;

    protected $cypher;

    public function __construct(\Zend\Crypt\BlockCipher $cypher)
    {
        $this->cypher = $cypher;
        $this->session = new Container('rcm_user_manager');
    }

    /**
     * @return \Rcm\Entity\User | null
     */
    public function getLoggedInUser()
    {
        if (is_a($this->session->user, '\Rcm\Entity\User')) {
            return $this->session->user;
        }
    }

    function setLoggedInUser(User $user)
    {
        $this->session->user = $user;

        $adminPermissions = $this->entityMgr
            ->getRepository('\Rcm\Entity\AdminPermissions')
            ->findOneByAccountNumber($user->getAccount()->getAccountNumber());

        if ($adminPermissions) {
            $this->session->adminPermissions = $adminPermissions;
        }
    }

    function clearLoggedInUser(){
        $this->session->user = null;
        $this->session->adminPermissions = null;
    }

    /**
     * Ensures that the current-user admin permissions object in the session is
     * valid and returns it
     *
     * @return \Rcm\Entity\AdminPermissions|null
     */
    public function getLoggedInAdminPermissions()
    {
        $user = $this->getLoggedInUser();
        if(!$user){
            return null;
        }

        $adminPermissions = $this->session->adminPermissions;
        $userAccountNo=$user->getAccount()->getAccountNumber();

        if (
            $user
            && is_a($adminPermissions, '\Rcm\Entity\AdminPermissions')
            && $userAccountNo == $adminPermissions->getAccountNumber()
        ) {
            return $adminPermissions;
        }
    }


    public function loginUser($email, $password)
    {
        $user = $this->entityMgr->getRepository('\Rcm\Entity\User')
            ->findOneBy(
            array(
                'email' => $email,
            )
        );

        if ($user) {

            $actualPassword = $user->getPassword($this->cypher);

            if ($password == $actualPassword) {
                $this->setLoggedInUser($user);
            }

        }

        return $user;
    }

    public function newUser($email, $password)
    {
        if(!is_a($this->cypher,'\Zend\Crypt\BlockCipher')){
            throw new \Exception(
                'User Manager is missing required dependencies. This ' .
                'is likely because you are running the open source installer' .
                'but have a non-open-source user manager enabled.'
            );
        }
        $user = new \Rcm\Entity\User();
        $user->setEmail($email);
        $user->setPassword($password, $this->cypher);
        $this->entityMgr->persist($user);
        $this->entityMgr->flush();
    }
}