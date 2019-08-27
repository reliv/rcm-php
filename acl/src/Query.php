<?php

namespace Rcm\Acl;

class Query
{
    /**
     * Action we are looking for
     */
    protected $action;
    /**
     * GroupIds that may have this action
     */
    protected $groupIds;
    /**
     * Resource properties that may identify rules
     */
    protected $properties;

    /**
     * Query constructor.
     * @param $action
     * @param $groupIds
     * @param $properties
     */
    public function __construct(string $action, array $groupIds, array $properties)
    {
        $this->action = $action;
        $this->groupIds = $groupIds;
        $this->properties = $properties;
    }

    /**
     * @return mixed
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getGroupNames()
    {
        return $this->groupIds;
    }

    /**
     * @return mixed
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
