<?php
/**
 * Service Factory for the Session Manager
 *
 * This file contains the factory needed to generate a Session Manager.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace Rcm\Factory;

use DoctrineModule\Cache\ZendStorageCache;
use Rcm\Exception\InvalidArgumentException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Config\ConfigInterface;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SaveHandler\SaveHandlerInterface;
use Zend\Session\SessionManager;
use Zend\Session\Storage\StorageInterface;
use Zend\Session\Validator\ValidatorInterface;

/**
 * Service Factory for the Session Manager
 *
 * Factory for the Session Manager.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @todo      Refactor and move to model object.  Also look at simplifying the factory
 *       overall.
 */
class SessionManagerFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return ZendStorageCache
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        if (!isset($config['session'])) {
            $sessionManager = new SessionManager();
            Container::setDefaultManager($sessionManager);

            return $sessionManager;
        }

        $session = $config['session'];

        $sessionConfig = $this->getSessionConfig(
            $serviceLocator,
            $session
        );

        $sessionStorage = $this->getSessionStorage(
            $serviceLocator,
            $session
        );

        $sessionSaveHandler = $this->getSessionSaveHandler(
            $serviceLocator,
            $session
        );

        $sessionManager = new SessionManager(
            $sessionConfig,
            $sessionStorage,
            $sessionSaveHandler
        );

        $this->setValidatorChain(
            $sessionManager,
            $serviceLocator,
            $session
        );

        Container::setDefaultManager($sessionManager);

        return $sessionManager;
    }

    /**
     * Build the session config object
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     * @param array                   $sessionConfig  Session Config
     *
     * @return \Zend\Session\Config\ConfigInterface|SessionConfig
     * @throws InvalidArgumentException
     */
    protected function getSessionConfig(
        ServiceLocatorInterface $serviceLocator,
        $sessionConfig
    ) {
        if (empty($sessionConfig)
            || empty($sessionConfig['config'])
        ) {
            return new SessionConfig();
        }

        $class = '\Zend\Session\Config\SessionConfig';
        $options = [];

        if (isset($sessionConfig['config']['class'])
        ) {
            $class = $sessionConfig['config']['class'];
        }

        if (isset($sessionConfig['config']['options'])
        ) {
            $options = $sessionConfig['config']['options'];
        }

        /** @var \Zend\Session\Config\ConfigInterface $sessionConfigObject */
        if ($serviceLocator->has($class)) {
            $sessionConfigObject = $serviceLocator->get($class);
        } else {
            $sessionConfigObject = new $class();
        }

        if (!$sessionConfigObject instanceof ConfigInterface) {
            throw new InvalidArgumentException(
                'Session Config class must implement '
                . '\Zend\Session\Config\ConfigInterface'
            );
        }

        $sessionConfigObject->setOptions($options);

        return $sessionConfigObject;
    }

    /**
     * Get Session Storage
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     * @param array                   $sessionConfig  Session Config
     *
     * @return StorageInterface
     * @throws InvalidArgumentException
     */
    protected function getSessionStorage(
        ServiceLocatorInterface $serviceLocator,
        $sessionConfig
    ) {
        if (empty($sessionConfig['storage'])) {
            return null;
        }

        if ($serviceLocator->has($sessionConfig['storage'])) {
            $storage = $serviceLocator->get($sessionConfig['storage']);
        } else {
            $storage = new $sessionConfig['storage'];
        }

        if (!$storage instanceof StorageInterface) {
            throw new InvalidArgumentException(
                'Session Storage class must implement '
                . 'Zend\Session\SaveHandler\SaveHandlerInterface'
            );
        }

        return $storage;
    }

    /**
     * Get the Session Save Handler
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     * @param array                   $sessionConfig  Session Config Array
     *
     * @return null|SaveHandlerInterface
     * @throws InvalidArgumentException
     */
    protected function getSessionSaveHandler(
        ServiceLocatorInterface $serviceLocator,
        $sessionConfig
    ) {
        if (!isset($sessionConfig['save_handler'])) {
            return null;
        }

        // Setting with invokable currently not implemented.  No session
        // Save handler available in ZF2 that's currently invokable is available
        // for testing.

        /** @var SaveHandlerInterface $sessionSaveHandler */
        $saveHandler = $serviceLocator->get($sessionConfig['save_handler']);

        if (!$saveHandler instanceof SaveHandlerInterface) {
            throw new InvalidArgumentException(
                'Session Save Handler class must implement '
                . 'Zend\Session\Storage\StorageInterface'
            );
        }

        return $saveHandler;
    }

    /**
     * Attach the Service Validators to the Session
     *
     * @param SessionManager          $sessionManager Zend Session Manager
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     * @param array                   $sessionConfig  Session Config Array
     *
     * @return void
     * @throws InvalidArgumentException
     */
    protected function setValidatorChain(
        SessionManager $sessionManager,
        ServiceLocatorInterface $serviceLocator,
        $sessionConfig
    ) {
        if (!isset($sessionConfig['validators'])
            || !is_array($sessionConfig['validators'])
        ) {
            return;
        }

        $chain = $sessionManager->getValidatorChain();

        foreach ($sessionConfig['validators'] as &$validator) {
            if ($serviceLocator->has($validator)) {
                $validator = $serviceLocator->get($validator);
            } else {
                $validator = new $validator();
            }

            if (!$validator instanceof ValidatorInterface) {
                throw new InvalidArgumentException(
                    'Session Save Handler class must implement '
                    . 'Zend\Session\Validator\ValidatorInterface'
                );
            }

            $chain->attach(
                'session.validate',
                [$validator, 'isValid']
            );
        }
    }
}
