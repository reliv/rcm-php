<?php
namespace Rcm\Factory;

use Zend\ServiceManager\ServiceManager;

class DoctrineInjector
{
    protected $serviceMgr;

    public function __construct(ServiceManager $serviceMgr)
    {
        $this->serviceMgr = $serviceMgr;
    }

    public function postLoad($eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if (is_subclass_of($entity, '\Rcm\Entity\User')) {
            $entity->setPasswordCypher($this->serviceMgr->get('cypher'));
        }
    }
}
