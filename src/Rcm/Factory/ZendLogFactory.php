<?php

namespace Rcm\Factory;

use Zend\Log\Writer\Noop;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Log\Logger;

/**
 * Service Factory for the Zend Log Service
 *
 * Factory for the Zend Log Service
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 */
class ZendLogFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return Logger
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        if (empty($config['rcmLogger']['writer'])) {
            $writer = new Noop();
        } else {
            $writer = $serviceLocator->get(
                $config['rcmLogger']['writer']
            );
        }

        $logger = new Logger();
        $logger->addWriter($writer);

        return $logger;
    }
}
