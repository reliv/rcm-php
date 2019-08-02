<?php

namespace Rcm\Acl;

class QueryResult
{
    protected $effect;

    /**
     * QueryResult constructor.
     * @param $effect
     */
    public function __construct(string $effect)
    {
        $this->effect = $effect;
    }

    /**
     * @return mixed
     */
    public function getEffect(): string
    {
        return $this->effect;
    }
}
