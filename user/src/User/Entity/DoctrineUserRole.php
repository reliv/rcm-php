<?php

namespace RcmUser\User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author James Jervis - https://github.com/jerv13
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_user_user_role")
 */
class DoctrineUserRole extends UserRole
{
    /**
     * @var integer $id
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string $userId
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $userId;
    /**
     * @var string $roleId
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $roleId;
}
