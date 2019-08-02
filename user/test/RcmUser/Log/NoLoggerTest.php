<?php

namespace RcmUser\Test\Log;

require_once __DIR__ . '/../../Zf2TestCase.php';

use RcmUser\Log\NoLogger;

/**
 * Class LoggerTest
 *
 * LoggerTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Log
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Log\Logger
 */
class NoLoggerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test
     *
     * @return void
     */
    public function test()
    {

        /** @var \RcmUser\Log\Logger $logger */
        $logger = new NoLogger(\Zend\Log\Logger::ERR);

        $loglev = $logger->getLogLevel();

        $this->assertEquals(
            \Zend\Log\Logger::ERR,
            $loglev
        );

        $can = $logger->canLog('ERR');

        $this->assertTrue($can);

        $can = $logger->canLog('DEBUG');

        $this->assertFalse($can);

        $logResult = $logger->emerg('message');

        $this->assertInstanceOf('\Zend\Log\LoggerInterface', $logResult);

        $logResult = $logger->alert('message');

        $this->assertInstanceOf('\Zend\Log\LoggerInterface', $logResult);

        $logResult = $logger->crit('message');

        $this->assertInstanceOf('\Zend\Log\LoggerInterface', $logResult);

        $logResult = $logger->err('message');

        $this->assertInstanceOf('\Zend\Log\LoggerInterface', $logResult);

        $logResult = $logger->warn('message');

        $this->assertInstanceOf('\Zend\Log\LoggerInterface', $logResult);

        $logResult = $logger->notice('message');

        $this->assertInstanceOf('\Zend\Log\LoggerInterface', $logResult);

        $logResult = $logger->info('message');

        $this->assertInstanceOf('\Zend\Log\LoggerInterface', $logResult);

        $logResult = $logger->debug('message');

        $this->assertInstanceOf('\Zend\Log\LoggerInterface', $logResult);
    }
}
