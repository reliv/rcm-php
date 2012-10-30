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
     * Sets the doctrine entity manager
     *
     * @param $entityMgr EntityManager doctrine entity manager
     *
     * @return null
     */
    function setEm(EntityManager $entityMgr){
        $this->entityMgr = $entityMgr;
    }

    function __construct(EntityManager $entityMgr){
        $this->entityMgr = $entityMgr;
    }
}