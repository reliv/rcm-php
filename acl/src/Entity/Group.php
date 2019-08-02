<?php

namespace Rcm\Acl\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Rcm\Acl\Rule;

/**
 * @ORM\Entity
 * @ORM\Table(name="rcm_acl_group")
 */
class Group
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
     * @var array
     *
     * @ORM\Column(type="json")
     */
    protected $rules;

    /**
     * @ORM\OneToMany(targetEntity="Rcm\Acl\Entity\UserGroup", mappedBy="group")
     */
    protected $userGroups;

    public function __construct()
    {
        $this->userGroups = new ArrayCollection();
    }

    protected static function ruleJsonToRuleObject($ruleJson)
    {
        return new Rule($ruleJson['effect'], $ruleJson['actions'], $ruleJson['properties']);
    }

    /**
     * @return Rule[]
     */
    public function getRules(): array
    {
        return array_map([Group::class, 'ruleJsonToRuleObject'], $this->rules);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
