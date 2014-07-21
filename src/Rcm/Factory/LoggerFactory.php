<?php

namespace Rcm\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Rcm\Service\Logger;

/**
 * Service Factory for Rcm's Logger
 *
 * Factory for the Rcm's Logger
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
class LoggerFactory implements FactoryInterface
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
        /** @var \Zend\Log\Logger $zendLogger */
        $zendLogger = $serviceLocator->get('Rcm\Service\ZendLogger');
        return new Logger($zendLogger);
    }
}
