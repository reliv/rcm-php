<?php

namespace Rcm\UserManagement;

class DoctrineUserManager extends \Rcm\Model\EntityMgrAware
    implements UserManagerInterface
{
    protected $session;

    protected $cypher;

    public function __construct(\Zend\Crypt\BlockCipher $cypher)
    {
        $this->cypher=$cypher;
        $this->session = new \Zend\Session\Container('rcm_user_manager');
    }

    /**
     * @return \Rcm\Entity\User | null
     */
    public function getLoggedInUser()
    {
        if (!empty($this->session->loggedInUser)) {

            $person = $this->entityMgr->getRepository('\Rcm\Entity\User')
                ->findOneByUserId($this->session->loggedInUser);

            return $person;

        }

    }

    public function login($email, $password)
    {
        $person = $this->entityMgr->getRepository('\Rcm\Entity\User')
            ->findOneBy(
            array(
                'email' => $email,
            )
        );

        if($person){

            $actualPassword=$person->getPassword($this->cypher);

            if ($password == $actualPassword) {
                $this->session->loggedInUser = $person;
            }

        }

        return $person;
    }

    public function newUser($email, $password)
    {
        $person = new \Rcm\Entity\User();
        $person->setEmail($email);
        $person->setPassword($password, $this->cypher);
        $this->entityMgr->persist($person);
        $this->entityMgr->flush();
    }
}