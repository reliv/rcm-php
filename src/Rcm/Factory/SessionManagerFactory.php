<?php

namespace Rcm\Factory;

use \Zend\Session\Container;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Cache\ZendStorageCache;
use Zend\Session\SessionManager;


class SessionManagerFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return ZendStorageCache
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        if (isset($config['session'])) {
            $session = $config['session'];

            $sessionConfig = null;
            if (isset($session['config'])) {
                $class = isset($session['config']['class'])
                    ? $session['config']['class']
                    : 'Zend\Session\Config\SessionConfig';

                $options = isset($session['config']['options'])
                    ? $session['config']['options'] : array();

                /** @var \Zend\Session\Config\ConfigInterface $sessionConfig */
                $sessionConfig = new $class();
                $sessionConfig->setOptions($options);
            }

            $sessionStorage = null;
            if (isset($session['storage'])) {
                $class = $session['storage'];

                /** @var \Zend\Session\Storage\StorageInterface $sessionStorage */
                $sessionStorage = new $class();
            }

            $sessionSaveHandler = null;
            if (isset($session['save_handler'])) {
                /** @var \Zend\Session\SaveHandler\SaveHandlerInterface $sessionSaveHandler */
                $sessionSaveHandler = $serviceLocator->get(
                    $session['save_handler']
                );
            }

            $sessionManager
                = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);

            if (isset($session['validator'])) {
                $chain = $sessionManager->getValidatorChain();
                foreach ($session['validator'] as $validator) {
                    $validator = new $validator();
                    $chain->attach(
                        'session.validate',
                        array($validator, 'isValid')
                    );

                }
            }
        } else {
            $sessionManager = new SessionManager();
        }

        Container::setDefaultManager($sessionManager);
        return $sessionManager;
    }
}
