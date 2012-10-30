<?php

namespace Rcm\Controller;

use \Zend\Mvc\Controller\AbstractActionController,
    \Doctrine\ORM\EntityManager;

class EntityMgrAwareController extends AbstractActionController
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