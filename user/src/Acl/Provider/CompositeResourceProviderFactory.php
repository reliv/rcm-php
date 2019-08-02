<?php

namespace RcmUser\Acl\Provider;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CompositeResourceProvider
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class CompositeResourceProviderFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return CompositeResourceProvider
     */
    public function __invoke($serviceLocator)
    {
        $config = $serviceLocator->get(
            'Config '
        );

        $providerConfig = $config['RcmUser']['Acl\Config']['ResourceProviders'];

        /** @var \RcmUser\Acl\Builder\ResourceProviderBuilder $resourceProviderBuilder */
        $resourceProviderBuilder = $serviceLocator->get(
            \RcmUser\Acl\Builder\ResourceProviderBuilder::class
        );

        /** @var \RcmUser\Acl\Cache\ResourceCache $resourceCache */
        $resourceCache = $serviceLocator->get(
            \RcmUser\Acl\Cache\ResourceCache::class
        );

        /** @var \RcmUser\Acl\Builder\AclResourceBuilder $resourceBuilder */
        $resourceBuilder = $serviceLocator->get(
            \RcmUser\Acl\Builder\AclResourceBuilder::class
        );

        $service = new CompositeResourceProvider(
            $resourceCache,
            $resourceBuilder
        );

        foreach ($providerConfig as $providerId => $providerData) {
            $provider = $resourceProviderBuilder->build($providerData, $providerId);
            $service->add($provider);
        }

        return $service;
    }
}
