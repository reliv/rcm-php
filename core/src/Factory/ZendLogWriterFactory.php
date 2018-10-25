<?php

namespace Rcm\Factory;

use Zend\Log\Writer\Stream;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Zend Log Writer
 *
 * Factory for the Zend Log Writer
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
class ZendLogWriterFactory
{
    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return Stream
     */
    public function __invoke($serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        $path = $config['rcmLogWriter']['logPath'];

        $writer = new Stream($path);

        return $writer;
    }
}
