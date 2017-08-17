<?php

namespace Rcm\Factory;

use Rcm\Acl\ResourceName;
use Rcm\Acl\ResourceProvider;
use Rcm\Service\CurrentSite;
use Zend\ServiceManager\ServiceLocatorInterface;

class AclResourceProviderFactory
{
    /**
     * Creates Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     *
     * @return ResourceProvider
     */
    public function __invoke($serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        $aclConfig = [];

        if (!empty($config['Rcm']['Acl'])) {
            $aclConfig = $config['Rcm']['Acl'];
        }

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $serviceLocator->get(CurrentSite::class);
        /** @var ResourceName $resourceName */
        $resourceName = $serviceLocator->get(ResourceName::class);

        return new ResourceProvider(
            $aclConfig,
            $currentSite,
            $resourceName
        );
    }
}
