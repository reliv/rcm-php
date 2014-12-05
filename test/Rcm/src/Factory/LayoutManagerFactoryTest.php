<?php
/**
 * Test for Factory LayoutManagerFactory
 *
 * This file contains the test for the LayoutManagerFactory.
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
 * @link      http://github.com/reliv
 */

namespace RcmTest\Factory;

require_once __DIR__ . '/../../../autoload.php';

use Rcm\Factory\LayoutManagerFactory;
use Rcm\Service\LayoutManager;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory LayoutManagerFactory
 *
 * Test for Factory LayoutManagerFactory
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 */
class LayoutManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\LayoutManagerFactory
     */
    public function testCreateService()
    {
        $sm = new ServiceManager();
        $sm->setService('config', []);

        $factory = new LayoutManagerFactory();
        $object = $factory->createService($sm);

        $this->assertTrue($object instanceof LayoutManager);
    }
}