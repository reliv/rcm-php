<?php

namespace Rcm\Acl;

class Rule
{
    /**
     * Effect on action
     */
    protected $effect;
    /**
     * Action name
     */
    protected $actions;
    /**
     * Security properties
     */
    protected $properties;

    /**
     * Rule constructor.
     * @param $effect
     * @param $actions
     * @param $properties
     */
    public function __construct(string $effect, $actions, $properties)
    {
        $this->effect = $effect;
        $this->actions = $actions;
        $this->properties = $properties;
    }

    /**
     * @return mixed
     */
    public function getEffect(): string
    {
        return $this->effect;
    }

    /**
     * @return mixed
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @return mixed
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
