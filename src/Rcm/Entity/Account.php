<?php

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM,
    \Doctrine\Common\Collections\ArrayCollection;

class Account
{
    /**
     * @var integer Account Number
    protected $accountNumber;

    /**
     * @var \Rcm\Entity\User primary person on the account
     */
    protected $primaryUser;

    protected $users;

    protected $accountNumber;

    function __construct()
    {
        $this->people = New ArrayCollection();
    }

    public function setPeople($people)
    {
        $this->people = $people;
    }

    public function getPeople()
    {
        return $this->people;
    }

    public function setUsers($users)
    {
        $this->users = $users;
    }

    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Sets the AccountNumber property
     *
     * @param int $accountNumber
     *
     * @return null
     *
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
    }

    /**
     * Gets the AccountNumber property
     *
     * @return int AccountNumber
     *
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    public function setPrimaryUser(\Rcm\Entity\User $primaryUser)
    {
        $this->primaryUser = $primaryUser;
        if (!$this->people->contains($primaryUser)) {
            $this->addUser($primaryUser);
        }
    }

    public function getPrimaryUser()
    {
        return $this->primaryUser;
    }

    function addUser(\Rcm\Entity\User $person)
    {
        $this->people->add($person);
    }

}