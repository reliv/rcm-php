<?php

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM,
    \Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="rcm_account")
 */
class Account
{
    /**
     * @var integer Account Number
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $accountNumber;

    /**
     * @var \Rcm\Entity\User primary person on the account
     *
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="primaryUserId", referencedColumnName="userId")
     */
    protected $primaryUser;

    /**
     * @ORM\OneToMany(targetEntity="Account", mappedBy="account")
     */
    protected $users;

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