<?php

namespace Rcm\Model;

use \Doctrine\ORM\EntityManager;

abstract class EntityMgrAware
{
    /**
     * @var \Doctrine\ORM\EntityManager entity manager
     */
    protected $entityMgr;

    /**
     * Constructor
     *
     * @param \Doctrine\ORM\EntityManager $entityMgr doctrine entity manager
     */
    function __construct(EntityManager $entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    /**
     * Sets the doctrine entity manager - useful for testing
     *
     * @param $entityMgr EntityManager doctrine entity manager
     *
     * @return null
     */
    function setEm(EntityManager $entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }
}