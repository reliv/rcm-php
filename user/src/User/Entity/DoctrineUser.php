<?php

namespace RcmUser\User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author James Jervis - https://github.com/jerv13
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_user_user")
 */
class DoctrineUserInterface extends UserAbstract implements UserInterface
{
    /**
     * @var string $id
     * @ORM\Id
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $id = null;

    /**
     * @var string $username
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    protected $username;

    /**
     * @var string $password
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $password;

    /**
     * @var string $state
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $state = UserInterface::STATE_DISABLED;

    /**
     * @var string $email
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @var string $name
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;
}
