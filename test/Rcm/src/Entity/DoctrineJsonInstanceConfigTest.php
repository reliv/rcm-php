<?php
/**
 * Unit Test for the Country Entity
 *
 * This file contains the unit test for the Country Entity
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

require_once __DIR__ . '/../../../autoload.php';

use Rcm\Entity\InstanceConfig;

/**
 * Unit Test for the Country Entity
 *
 * Unit Test for the Country Entity
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class InstanceConfigTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Rcm\Entity\InstanceConfig */
    protected $instanceConfig;

    /**
     * Setup Tests
     *
     * @return void
     */
    public function setUp()
    {
        $this->instanceConfig = new InstanceConfig();
    }

    /**
     * Test Set Get Instance Id
     *
     * @return void
     *
     * @covers \Rcm\Entity\InstanceConfig
     */
    public function testSetGetInstanceId()
    {
        $instanceId = 789;
        $this->instanceConfig->setInstanceId($instanceId);
        $this->assertEquals(
            $this->instanceConfig->getInstanceId(), $instanceId
        );
    }

    /**
     * Test Set Get Instance Config
     *
     * @return void
     *
     * @covers \Rcm\Entity\InstanceConfig
     */
    public function testSetGetInstanceConfig()
    {
        $instanceConfig = array(array('key' => 'val'));
        $this->instanceConfig->setInstanceId($instanceConfig);
        $this->assertEquals(
            $this->instanceConfig->getInstanceId(), $instanceConfig
        );
    }
} 