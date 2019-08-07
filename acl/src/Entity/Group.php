<?php

namespace Rcm\Acl\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Rcm\Acl\Rule;

class Group
{
    protected $name;

    protected $rules;

    protected $customProps;

    /**
     * Group constructor.
     * @param $name
     * @param $rules
     */
    public function __construct($name, array $rules, array $customProps)
    {
        $this->name = $name;
        $this->rules = $rules;
        $this->customProps = $customProps;
    }

    /**
     * @return Rule[]
     */
    public function getRules(): array
    {
        return array_map([Group::class, 'ruleJsonToRuleObject'], $this->rules);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getCustomProps(): array
    {
        return $this->customProps;
    }

    protected static function ruleJsonToRuleObject($ruleJson)
    {
        return new Rule($ruleJson['effect'], $ruleJson['actions'], $ruleJson['properties']);
    }
}
