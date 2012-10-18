<?php

namespace Rcm\Model\UserManagement;

class DoctrineUserManager extends \Rcm\Model\FactoryAbstract
    implements UserManagementInterface
{
    protected $session;

    protected $blockCypher;

    public function __construct(\Zend\Crypt\BlockCipher $blockCypher)
    {
        $this->blockCypher=$blockCypher;
        $this->session = new \Zend\Session\Container('rcm_user_manager');
    }

    /**
     * @return \Rcm\Entity\Person | null
     */
    public function getLoggedInPerson()
    {
        if (!empty($this->session->loggedInPerson)) {

            $person = $this->getEm()->getRepository('\Rcm\Entity\Person')
                ->findOneByPersonId($this->session->loggedInPerson);

            return $person;

        }

    }

    public function login($email, $password)
    {
        $person = $this->getEm()->getRepository('\Rcm\Entity\Person')
            ->findOneBy(
            array(
                'email' => $email,
            )
        );

        if($person){

            $actualPassword=$person->getPassword($this->blockCypher);

            if ($password == $actualPassword) {
                $this->session->loggedInPerson = $person;
            }

        }

        return $person;
    }

    public function newPerson($email, $password)
    {
        $person = new \Rcm\Entity\Person();
        $person->setEmail($email);
        $person->setPassword($password, $this->blockCypher);
        $this->getEm()->persist($person);
        $this->getEm()->flush();
    }
}