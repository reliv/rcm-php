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
     * @var \Rcm\Entity\Person primary person on the account
     *
     * @ORM\OneToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="primaryPersonId", referencedColumnName="personId")
     */
    protected $primaryPerson;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection people on the account,
     * this includes the primary person
     *
     * @ORM\OneToMany(
     *     targetEntity="Person",
     *     mappedBy="personId",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $people;

    function __construct(){
        $this->people=New ArrayCollection();
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

    public function setPrimaryPerson(\Rcm\Entity\Person $primaryPerson)
    {
        $this->primaryPerson = $primaryPerson;
        if(!$this->people->contains($primaryPerson)){
            $this->addPerson($primaryPerson);
        }
    }

    public function getPrimaryPerson()
    {
        return $this->primaryPerson;
    }

    function addPerson(\Rcm\Entity\Person $person){
        $this->people->add($person);
    }

}