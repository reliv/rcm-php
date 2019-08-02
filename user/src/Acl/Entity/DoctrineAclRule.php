<?php

namespace RcmUser\Acl\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author James Jervis - https://github.com/jerv13
 * @ORM\Entity
 * @ORM\Table(name="rcm_user_acl_rule")
 */
class DoctrineAclRule extends AclRule
{

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $roleId;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $rule = AclRule::RULE_ALLOW;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $resourceId;

    /**
     * @deprecated Use $privileges
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $privilege;

    /**
     * @var array
     *
     * OLD: ORM\Column(type="string", length=255, nullable=true)
     * @ORM\Column(type="json_array")
     */
    protected $privileges = [];

    /**
     * setId
     *
     * @param int $id id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * getId
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
