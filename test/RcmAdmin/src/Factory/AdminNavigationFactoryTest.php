<?php
/**
 * Test for Factory AdminNavigationFactory
 *
 * This file contains the test for the AdminNavigationFactory.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace RcmAdminTest\Factory;

require_once __DIR__ . '/../../../autoload.php';

use RcmAdmin\Factory\AdminNavigationFactory;

/**
 * Test for Factory AdminNavigationFactory
 *
 * Test for Factory AdminNavigationFactory
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 */
class AdminNavigationFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Get Name for container
     *
     * @return null
     * @covers \RcmAdmin\Factory\AdminNavigationFactory::getName
     */
    public function testGetName()
    {
        $factory = new AdminNavigationFactory();

        $reflectedClass = new \ReflectionClass($factory);

        $reflectedMethod = $reflectedClass->getMethod('getName');
        $reflectedMethod->setAccessible(true);

        $return = $reflectedMethod->invoke($factory);

        $this->assertEquals('RcmAdminMenu', $return);
    }
}