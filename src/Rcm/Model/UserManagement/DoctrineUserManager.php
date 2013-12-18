<?php

namespace Rcm\Model\UserManagement;

use Rcm\Model\EntityMgrAware;
use Zend\Crypt\BlockCipher;
use Zend\Session\Container;
use Rcm\Entity\AdminPermissions;
use Rcm\Entity\User;
use Zend\Session\SessionManager;

class DoctrineUserManager extends EntityMgrAware
    implements UserManagerInterface
{
    protected $session;

    protected $cypher;

    /**
     * @var SessionManager
     */
    protected $sessionMgr;

    const SESSION_NAME = 'rcm_user_manager';

    public function __construct(
        BlockCipher $cypher,
        SessionManager $sessionMgr
    )
    {
        $this->cypher = $cypher;
        $this->session = new Container(self::SESSION_NAME);
        $this->sessionMgr = $sessionMgr;
    }

    public function logout()
    {
        $this->destroyLoginSession();
    }

    public function saveUser(User $user)
    {

    }


    public function isCurrentUser($username)
    {
        $user = $this->entityMgr->getRepository('\Rcm\Entity\User')
            ->findOneByUsername($username);

        if ($user) {
            return true;
        }

        return false;
    }

    /**
     * @return \Rcm\Entity\User | null
     */
    public function getLoggedInUser()
    {
        if (!$this->session->userId) {
            return null;
        }
        return $this->entityMgr->getRepository('\Rcm\Entity\User')
            ->find($this->session->userId);
    }

    /**
     * @param \Rcm\Entity\User $user
     * @TODO REMOVE $junk BUT KEEP STRICT STANDARDS PASSING
     */
    function setLoggedInUser(User $user, $junk)
    {
        $this->session['userId'] = $user->getUserId();
    }

    function destroyLoginSession()
    {
        $this->session['user']=null;
        $this->session->getManager()->getStorage()->clear(self::SESSION_NAME);
    }

    /**
     * @return \Rcm\Entity\AdminPermissions|null
     */
    public function getLoggedInAdminPermissions()
    {

        //HACK FOR DEVELOPING WHEN LOGIN BACKEND IS DOWN
        //NEVER LET THIS GO LIVE
//        return $this->entityMgr
//            ->getRepository('\Rcm\Entity\AdminPermissions')
//            ->findOneByAccountNumber(7);


        $user = $this->getLoggedInUser();
        if (!$user) {
            return null;
        }
        return $this->entityMgr
            ->getRepository('\Rcm\Entity\AdminPermissions')
            ->findOneByAccountNumber($user->getAccount()->getAccountNumber());
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
                $this->setLoggedInUser($user, null);
            }

        }

        return $user;
    }

    public function newUser($email, $password, $accountNumber)
    {
        if (!is_a($this->cypher, '\Zend\Crypt\BlockCipher')) {
            throw new \Exception(
                'User Manager is missing required dependencies. This ' .
                'is likely because you are running the open source installer' .
                'but have a non-open-source user manager enabled.'
            );
        }
        $user = new \Rcm\Entity\User();
        $user->setEmail($email);
        $user->setPassword($password, $this->cypher);
        $account = new \Rcm\Entity\Account();
        $account->setAccountNumber($accountNumber);
        $user->setAccount($account);
        $this->entityMgr->persist($account);
        $this->entityMgr->persist($user);
        $this->entityMgr->flush();
    }
}