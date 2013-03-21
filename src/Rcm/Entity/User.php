<?php

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM,
Rcm\Exception\InvalidArgumentException;

/**
 * @ORM\Entity
 * @ORM\Table(name="rcm_user")
 */
class User
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $userId;

    /**
     * @var string  first name
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @var string  middle initial
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $middleInitial;

    /**
     * @var string  last name
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $lastName;

    /**
     * @var \DateTime  last name
     *
     * @ORM\Column(type="date", nullable=true)
     */
    protected $dateOfBirth;

    /**
     * @ORM\OneToOne(targetEntity="Address")
     * @ORM\JoinColumn(name="billAddressId", referencedColumnName="addressId")
     */
    protected $billAddress;

    /**
     * @ORM\OneToOne(targetEntity="Address")
     * @ORM\JoinColumn(name="shipAddressId", referencedColumnName="addressId")
     */
    protected $shipAddress;

    /**
     * @var boolean gender is male
     * *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $genderIsMale;

    /**
     * @var string email
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @var string user id
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $username;

    /**
     * @var string password
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $password;

    /**
     * @param Account $account
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="users")
     * @ORM\JoinColumn(name="accountNumber", referencedColumnName="accountNumber")
     */
    protected $account;



    /**
     * @var integer phone number
     *
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $daytimePhone;

    /**
     * @var integer phone number
     *
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $eveningPhone;

    /**
     * @var integer phone number
     *
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $cellPhone;

    /**
     * @var integer phone number
     *
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $faxPhone;

    /**
     * @var \DateTime when this entity was created
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdDate;

    /**
     * @var string Account Type
     *
     * @ORM\Column(type="string")
     */
    protected $accountType;

    /**
     * @var string rank
     *
     * @ORM\Column(type="string")
     */
    protected $rank;

    /**
     * @var integer social security number
     *
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $ssn;

    function __construct()
    {
        $this->setCreatedDate(new \DateTime("now"));
    }

    function toArray() {
        return get_object_vars($this);
    }

    function digitsOnly($value)
    {
        return preg_replace('/[^0-9]*/', '', $value);
    }

    /**
     * @param string $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * @return string
     */
    public function getRank()
    {
        return $this->rank;
    }

    public function setPassword($password, \Zend\Crypt\BlockCipher $cypher)
    {
        if(empty($password)){
            $this->password = null;
        }
        $this->password = $cypher->encrypt($password);
    }

    public function getPassword(\Zend\Crypt\BlockCipher $cypher)
    {
        if(empty($this->password)){
            return null;
        }
        return $cypher->decrypt($this->password);
    }

    function setDateOfBirthViaMMDDYYY($dateOfBirth, $sanityCheck = true)
    {
        $parts = explode('/', $dateOfBirth);
        $dateObj = null;
        if (
            //Using check date before creating the object helps reject dates
            //like 2/30/1990
            count($parts) == 3
            && (checkdate($parts[0], $parts[1], $parts[2]))
        ) {
            $dateObj = \DateTime::createFromFormat('m/d/Y', $dateOfBirth);
        }
        $now = new \DateTime();
        $twoHundredYearsAgo=clone($now);
        $twoHundredYearsAgo = $twoHundredYearsAgo->sub(
            new \DateInterval('P200Y')
        );
        if (
            !$dateObj
            || ($sanityCheck
                && ($dateObj > $now || $twoHundredYearsAgo > $dateObj)
            )
        ) {
            throw new InvalidArgumentException();
        } else {
            $this->setDateOfBirth($dateObj);
        }

    }

    function getDateOfBirthViaMMDDYYY()
    {
        if(is_object($this->dateOfBirth)){
            return $this->dateOfBirth->format('m/d/Y');
        }
    }

    /**
     * Sets the DateOfBirth property
     *
     * @param \DateTime $dateOfBirth
     *
     * @return null
     *
     */
    function setDateOfBirth($dateOfBirth)
    {
        if (isset($dateOfBirth) && !is_a($dateOfBirth, '\DateTime')) {
            throw new \InvalidArgumentException();
        }
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * Gets the DateOfBirth property
     *
     * @return \DateTime DateOfBirth
     *
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Sets the FirstName property
     *
     * @param string $firstName
     *
     * @return null
     *
     */
    public function setFirstName($firstName)
    {
        if ($firstName != strip_tags($firstName)) {
            throw new InvalidArgumentException();
        }
        $this->firstName = $firstName;
    }

    /**
     * Gets the FirstName property
     *
     * @return string FirstName
     *
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Sets the GenderIsMale property
     *
     * @param boolean $genderIsMale
     *
     * @return null
     *
     */
    public function setGenderIsMale($genderIsMale)
    {
        $this->genderIsMale = $genderIsMale;
    }

    /**
     * Gets the GenderIsMale property
     *
     * @return boolean GenderIsMale
     *
     */
    public function getGenderIsMale()
    {
        return $this->genderIsMale;
    }

    /**
     * Sets the LastName property
     *
     * @param string $lastName
     *
     * @return null
     *
     */
    public function setLastName($lastName)
    {
        if ($lastName != strip_tags($lastName)) {
            throw new InvalidArgumentException();
        }
        $this->lastName = $lastName;
    }

    /**
     * Gets the LastName property
     *
     * @return string LastName
     *
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Sets the MiddleInitial property
     *
     * @param string $middleInitial
     *
     * @return null
     *
     */
    public function setMiddleInitial($middleInitial)
    {
        if (
            $middleInitial != strip_tags($middleInitial)
            || strlen($middleInitial) > 1
        ) {
            throw new InvalidArgumentException();
        }
        $this->middleInitial = $middleInitial;
    }

    /**
     * Gets the MiddleInitial property
     *
     * @return string MiddleInitial
     *
     */
    public function getMiddleInitial()
    {
        return $this->middleInitial;
    }

    /**
     * Sets the UserId property
     *
     * @param int $userId
     *
     * @return null
     *
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Gets the UserId property
     *
     * @return int UserId
     *
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the Ssn property
     *
     * @param int|null $ssn
     *
     * @return null
     *
     */
    public function setSsn($ssn)
    {
        //Filter out anything that isn't a digit
        $ssn = $this->digitsOnly($ssn);
        //SSN's must be 9 digits
        if (!empty($ssn) && strlen($ssn) != 9) {
            throw new InvalidArgumentException();
        }
        $this->ssn = $ssn;
    }

    /**
     * Gets the Ssn property
     *
     * @return int Ssn
     *
     */
    public function getSsn()
    {
        return $this->ssn;
    }

    function getSsnMasked()
    {
        return '***-**-' . substr($this->ssn, -4);
    }

    public function setBillAddress($billAddress)
    {
        if (isset($billAddress) && !is_a($billAddress, '\Rcm\Entity\Address')) {
            throw new InvalidArgumentException();
        }
        $this->billAddress = $billAddress;
    }

    /**
     * @returns \Rcm\Entity\Address
     */
    public function getBillAddress()
    {
        return $this->billAddress;
    }

    /**
     * Sets the CreatedDate property
     *
     * @param string $createdDate
     *
     * @return null
     *
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * Gets the CreatedDate property
     *
     * @return string CreatedDate
     *
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    public function setShipAddress($shipAddress)
    {
        if (isset($shipAddress) && !is_a($shipAddress, '\Rcm\Entity\Address')) {
            throw new InvalidArgumentException();
        }
        $this->shipAddress = $shipAddress;
    }

    /**
     * @returns \Rcm\Entity\Address
     */
    public function getShipAddress()
    {
        return $this->shipAddress;
    }

    /**
     * Sets the CellPhone property
     *
     * @param int $cellPhone
     *
     * @return null
     *
     */
    public function setCellPhone($cellPhone)
    {
        $this->cellPhone = $this->digitsOnly($cellPhone);
    }

    /**
     * Gets the CellPhone property
     *
     * @return int CellPhone
     *
     */
    public function getCellPhone()
    {
        return $this->cellPhone;
    }

    /**
     * Sets the DaytimePhone property
     *
     * @param int $daytimePhone
     *
     * @return null
     *
     */
    public function setDaytimePhone($daytimePhone)
    {
        $this->daytimePhone = $this->digitsOnly($daytimePhone);
    }

    /**
     * Gets the DaytimePhone property
     *
     * @return int DaytimePhone
     *
     */
    public function getDaytimePhone()
    {
        return $this->daytimePhone;
    }

    /**
     * Sets the EveningPhone property
     *
     * @param int $eveningPhone
     *
     * @return null
     *
     */
    public function setEveningPhone($eveningPhone)
    {
        $this->eveningPhone = $this->digitsOnly($eveningPhone);
    }

    /**
     * Gets the EveningPhone property
     *
     * @return int EveningPhone
     *
     */
    public function getEveningPhone()
    {
        return $this->eveningPhone;
    }

    /**
     * Sets the FaxPhone property
     *
     * @param int $faxPhone
     *
     * @return null
     *
     */
    public function setFaxPhone($faxPhone)
    {
        $this->faxPhone = $this->digitsOnly($faxPhone);
    }

    /**
     * Gets the FaxPhone property
     *
     * @return int FaxPhone
     *
     */
    public function getFaxPhone()
    {
        return $this->faxPhone;
    }

    /**
     * Sets the Email property
     *
     * @param string $email
     *
     * @return null
     *
     */
    public function setEmail($email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new InvalidArgumentException();
        }
        $this->email = $email;
    }

    /**
     * Gets the Email addresss property
     *
     * @return string Email
     *
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the Username property
     *
     * @param string $username
     *
     * @return null
     *
     */
    public function setUsername($username)
    {
        if(!ctype_alnum($username)){
            throw new InvalidArgumentException();
        }
        $this->username = $username;
    }

    /**
     * Gets the Username property
     *
     * @return string Username
     *
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function getFullName()
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

    /**
     * @param string $accountType
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;
    }

    /**
     * @return string
     */
    public function getAccountType()
    {
        return $this->accountType;
    }


}