<?php


namespace RcmDoctrineJsonPluginStorageTest\Entity;


use Rcm\Entity\KeyValue;

class KeyValueTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \RcmDoctrineJsonPluginStorage\Entity\KeyValue */
    protected $keyValue;

    public function setUp()
    {
        $this->keyValue = new KeyValue();
    }

//    /**
//     * @covers \RcmDoctrineJsonPluginStorage\Entity\KeyValue
//     */
//    public function testSetGetKey()
//    {
//        $key =
//        $this->keyValue->setKey($key);
//        $this->assertEquals($this->keyValue->getKey(), $key);
//    }
//
//    /**
//     * @covers \RcmDoctrineJsonPluginStorage\Entity\KeyValue
//     */
//    public function testSetGetValue()
//    {
//        $this->keyValue->setValue($keyValue);
//        $this->assertEquals($this->keyValue->getValue(), $keyValue);
//    }

} 