<?php

namespace Rcm\SwitchUser\Restriction;

use Interop\Container\ContainerInterface;
use RcmUser\User\Entity\UserInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CompositeRestriction implements Restriction
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;
    /**
     * @var array
     */
    protected $restrictions = [];

    /**
     * @param array                                      $config
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     */
    public function __construct($config, $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        $this->buildRestrictions($config['Rcm\\SwitchUser']['restrictions']);
    }

    /**
     * buildRestrictions
     *
     * @param array $restrictionConfig
     *
     * @return void
     */
    protected function buildRestrictions($restrictionConfig)
    {
        foreach ($restrictionConfig as $serviceName) {
            /** @var Restriction $service */
            $service = $this->serviceLocator->get($serviceName);
            $this->add($service);
        }
    }

    /**
     * add
     *
     * @param Restriction $restriction
     *
     * @return void
     */
    public function add(Restriction $restriction)
    {
        $this->restrictions[] = $restriction;
    }

    /**
     * allowed
     *
     * @param UserInterface $adminUser
     * @param UserInterface $targetUser
     *
     * @return RestrictionResult
     */
    public function allowed(UserInterface $adminUser, UserInterface $targetUser)
    {
        /** @var Restriction $restriction */
        foreach ($this->restrictions as $restriction) {
            $result = $restriction->allowed($adminUser, $targetUser);
            if (!$result->isAllowed()) {
                return $result;
            }
        }

        return new RestrictionResult(true);
    }
}
