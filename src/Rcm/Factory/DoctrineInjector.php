<?php
namespace Rcm\Factory;

use Zend\ServiceManager\ServiceManager;

class DoctrineInjector
{
    protected $sm;

    public function __construct(ServiceManager $serviceManager){
        $this->sm = $serviceManager;
    }

    public function postLoad($eventArgs){
        $entity = $eventArgs->getEntity();

        if (is_subclass_of($entity, '\Rcm\Entity\User')) {
            $entity->setPasswordCypher($this->sm->get('cypher'));
        }
    }
}
