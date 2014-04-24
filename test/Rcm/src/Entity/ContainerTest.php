<?php
/**
 * Unit Test for the Controller Entity
 *
 * This file contains the unit test for the Controller Entity
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

namespace RcmTest\Entity;

require_once __DIR__ . '/../../../Base/BaseTestCase.php';

use Rcm\Entity\Container;
use RcmTest\Base\BaseTestCase;

/**
 * Unit Test for Controller Entity
 *
 * Unit Test for Controller Entity
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class ContainerTest extends BaseTestCase
{
    /** @var \Rcm\Entity\Container */
    protected $container;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->addModule('Rcm');
        parent::setUp();

        $this->container = new Container();
    }

    /**
     * Test Get And Set Container ID
     *
     * @return void
     *
     * @covers \Rcm\Entity\Container
     */
    public function testGetAndSetContainerId()
    {
        $id = 4;

        $this->container->setContainerId($id);

        $actual = $this->container->getContainerId();

        $this->assertEquals($id, $actual);
    }
}
 