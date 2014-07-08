<?php
/**
 * Unit Test for the Plugin Wrapper
 *
 * This file contains the unit test for the Plugin Wrapper
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

use Rcm\Entity\PluginInstance;
use Rcm\Entity\PluginWrapper;

/**
 * Unit Test for the Plugin Wrapper
 *
 * Unit Test for the Plugin Wrapper
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class PluginWrapperTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Rcm\Entity\PluginWrapper */
    protected $pluginWrapper;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->pluginWrapper = new PluginWrapper();
    }

    /**
     * Test Get and Set Plugin Wrapper Id
     *
     * @return void
     *
     * @covers \Rcm\Entity\PluginWrapper
     */
    public function testGetAndSetPluginWrapperId()
    {
        $id = 4;

        $this->pluginWrapper->setPluginWrapperId($id);

        $actual = $this->pluginWrapper->getPluginWrapperId();

        $this->assertEquals($id, $actual);
    }

    /**
     * Test Get and Set Layout Container
     *
     * @return void
     *
     * @covers \Rcm\Entity\PluginWrapper
     */
    public function testGetAndSetLayoutContainer()
    {
        $container = 4;

        $this->pluginWrapper->setLayoutContainer($container);

        $actual = $this->pluginWrapper->getLayoutContainer();

        $this->assertEquals($container, $actual);
    }

    /**
     * Test Get and Set Render Order Number
     *
     * @return void
     *
     * @covers \Rcm\Entity\PluginWrapper
     */
    public function testGetAndSetRenderOrderNumber()
    {
        $order = 4;

        $this->pluginWrapper->setRenderOrderNumber($order);

        $actual = $this->pluginWrapper->getRenderOrderNumber();

        $this->assertEquals($order, $actual);
    }

    /**
     * Test Get and Set Plugin Instance
     *
     * @return void
     *
     * @covers \Rcm\Entity\PluginWrapper
     */
    public function testGetAndSetInstance()
    {
        $plugin = new PluginInstance();
        $plugin->setInstanceId(44);

        $this->pluginWrapper->setInstance($plugin);

        $actual = $this->pluginWrapper->getInstance();

        $this->assertTrue($plugin instanceof PluginInstance);
        $this->assertEquals($plugin, $actual);
    }

    /**
     * Test Set Plugin Instance Only Accepts a Plugin Instance object
     *
     * @return void
     *
     * @covers \Rcm\Entity\PluginWrapper
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testSetCreatedDateOnlyAcceptsDateTime()
    {
        $this->pluginWrapper->setInstance(time());
    }

    /**
     * Test Get and Set Height
     *
     * @return void
     *
     * @covers \Rcm\Entity\PluginWrapper
     */
    public function testGetAndSetHeight()
    {
        $height = '140';

        $this->pluginWrapper->setHeight($height);

        $actual = $this->pluginWrapper->getHeight();

        $this->assertEquals($height . 'px', $actual);
    }

    /**
     * Test Get and Set Width
     *
     * @return void
     *
     * @covers \Rcm\Entity\PluginWrapper
     */
    public function testGetAndSetWidth()
    {
        $width = '140';

        $this->pluginWrapper->setWidth($width);

        $actual = $this->pluginWrapper->getWidth();

        $this->assertEquals($width . 'px', $actual);
    }

    /**
     * Test Get and Set Div Float
     *
     * @return void
     *
     * @covers \Rcm\Entity\PluginWrapper
     */
    public function testGetAndSetDivFloat()
    {
        $float = 'right';

        $this->pluginWrapper->setDivFloat($float);

        $actual = $this->pluginWrapper->getDivFloat();

        $this->assertEquals($float, $actual);
    }


}
 