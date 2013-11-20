<?php


namespace RcmTest\Entity;


use Rcm\Entity\Setting;

class SettingTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Rcm\Entity\Setting */
    protected $setting;

    public function setUp()
    {
        $this->setting = new Setting();
    }

    /**
     * @covers \Rcm\Entity\Setting
     */
    public function testSetGetName()
    {
        $name = 'testName';
        $this->setting->setName($name);
        $this->assertEquals($this->setting->getName(), $name);
    }

    /**
     * @covers \Rcm\Entity\Setting
     */
    public function testSetGetValue()
    {
        $value = 'testValue';
        $this->setting->setValue($value);
        $this->assertEquals($this->setting->getValue(), $value);
    }
} 