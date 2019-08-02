<?php

namespace RcmUser\Acl\Builder;

use RcmUser\Acl\Provider\ResourceProvider;
use RcmUser\Acl\Provider\ResourceProviderInterface;
use RcmUser\Exception\RcmUserException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ResourceProviderBuilder
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Builder
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ResourceProviderBuilder
{
    /**
     * @var ServiceLocatorInterface $serviceLocator
     */
    protected $serviceLocator;

    /**
     * ResourceProvider constructor.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * getServiceLocator
     *
     * @return ServiceLocatorInterface
     */
    protected function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * build
     *
     * @param mixed  $providerData
     * @param string $providerId
     *
     * @return ResourceProviderInterface
     * @throws RcmUserException
     */
    public function build(
        $providerData,
        $providerId = null
    ) {
        $provider = null;

        if ($providerData instanceof ResourceProviderInterface) {
            $provider = $providerData;
        }

        if (is_string($providerData)) {
            $provider = $this->getServiceLocator()->get($providerData);
        }

        if (is_array($providerData)) {
            $provider = new \RcmUser\Acl\Provider\ResourceProvider($providerData);
        }

        if ($provider instanceof ResourceProvider) {
            $provider->setProviderId($providerId);
        }

        if ($provider === null) {
            throw new RcmUserException(
                'ResourceProvider is not valid: ' . var_export(
                    $providerData,
                    true
                )
            );
        }

        if ($provider->getProviderId() === null) {
            throw new RcmUserException(
                'ResourceProvider is not valid, provider ID missing: ' . var_export(
                    $providerData,
                    true
                )
            );
        }

        return $provider;
    }
}
