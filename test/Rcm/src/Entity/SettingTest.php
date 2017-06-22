<?php

namespace RcmTest\Entity;

require_once __DIR__ . '/../../../autoload.php';

use Rcm\Entity\Setting;

/**
 * Unit Test for the Setting Entity
 *
 * Unit Test for the Setting Entity
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class SettingTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Rcm\Entity\Setting */
    protected $setting;

    /**
     * Setup For Tests
     *
     * @return void
     */
    public function setUp()
    {
        $this->setting = new Setting(
            'userId123'
        );
    }

    /**
     * Test Get and Set Name
     *
     * @return void
     *
     * @covers \Rcm\Entity\Setting
     */
    public function testSetGetName()
    {
        $name = 'testName';
        $this->setting->setName($name);
        $this->assertEquals($this->setting->getName(), $name);
    }

    /**
     * Test Get and Set Value
     *
     * @return void
     *
     * @covers \Rcm\Entity\Setting
     */
    public function testSetGetValue()
    {
        $value = 'testValue';
        $this->setting->setValue($value);
        $this->assertEquals($this->setting->getValue(), $value);
    }
}
