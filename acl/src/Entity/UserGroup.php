<?php

namespace Rcm\Acl\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="rcm_acl_user_group")
 */
class UserGroup
{
    /**
     * @var integer
     *
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $userId;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="Rcm\Acl\Entity\Group")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $group;
}
