<?php
/**
 * Test Country Database Entity
 *
 * Test Suite for the Doctorine 2 definition file for Country Objects. 
 * 
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category   Reliv
 * @package    Common\Tests\Entities
 * @author     Westin Shafer <wshafer@relivinc.com>
 * @copyright  2012 Reliv International
 * @license    License.txt New BSD License
 * @version    GIT: <git_id>
 * @link       http://ci.reliv.com/confluence
 */

namespace Rcm\Entity;

require_once __DIR__ . '/../../src/Rcm/Entity/Country.php';

/**
 * Tests for the Country Database Entity
 *
 * This test suite tests the Doctorine 2 definition file for Country Objects.
 * Note: This does not test the entity through a Database connection.  This
 * only tests the entity as if it was a standard object file.
 *
 * @category   Reliv
 * @package    Common\Tests\Entities
 * @author     Westin Shafer <wshafer@relivinc.com>
 * @copyright  2012 Reliv International
 * @license    License.txt New BSD License
 * @version    Release: 1.0
 * @link       http://ci.reliv.com/confluence
 */
class CountryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rcm\Entity\Country
     */
    protected $country;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        parent::setUp();

        $this->country = new \Rcm\Entity\Country();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        parent::tearDown();

        $this->country = null;
    }

    /**
     * Constructs the test case.
     */
    public function __construct ()
    {

    }

    //   Begin Tests  //

    /**
     * @covers Rcm\Entity\Country::getCountry
     */
    public function testGetCountry()
    {
        $this->country->setIso3('CAN');

        $values = $this->country->getCountry();

        $this->assertEquals($values, 'CAN');
    }

    /**
     * Test Country->getId() & Country->setId()
     *
     * @covers \Rcm\Entity\Country::getId
     * @covers \Rcm\Entity\Country::setId
     */
    public function testGetAndSetId()
    {
        $this->country->setId(3);
        $actual = $this->country->getId();

        $this->assertEquals(3, $actual);
    }

    /**
     * Test Country->setName() & Country->getName()
     *
     * @covers \Rcm\Entity\Country::getName
     * @covers \Rcm\Entity\Country::setName
     */
    public function testGetAndSetName()
    {
        $this->country->setName('Canada');
        $actual = $this->country->getName();

        $this->assertEquals('Canada', $actual);
    }

    /**
     * Test Country->setIso2 & Country->getIso2
     *
     * @covers Rcm\Entity\Country::setIso2
     * @covers Rcm\Entity\Country::getIso2
     */
    public function testGetAndSetIso2()
    {
        $this->country->setIso2('US');

        $value = $this->country->getIso2();

        $this->assertEquals('US', $value);
    }

    /**
     * Test Country->setIso3 & Country->getIso3
     *
     * @covers Rcm\Entity\Country::setIso3
     * @covers Rcm\Entity\Country::getIso3
     */
    public function testGetAndSetIso3()
    {
        $this->country->setIso3('USA');

        $value = $this->country->getIso3();

        $this->assertEquals('USA', $value);
    }

    /**
     * Test Country->setSpecialApiCountry & Country->getSpecialApiCountry
     *
     * @covers Rcm\Entity\Country::getSpecialApiCountry
     * @covers Rcm\Entity\Country::setSpecialApiCountry
     */
    public function testGetAndSetSpecialApiCountry()
    {
        $this->country->setSpecialApiCountry('GER');

        $value = $this->country->getSpecialApiCountry();

        $this->assertEquals('GER', $value);
    }

    /**
     * Test Country->setOldWebCountry & Country->getOldWebCountry
     *
     * @covers Rcm\Entity\Country::getOldWebCountry
     * @covers Rcm\Entity\Country::setOldWebCountry
     */
    public function testGetAndSetOldWebCountry()
    {
        $this->country->setOldWebCountry('TST');

        $value = $this->country->getOldWebCountry();

        $this->assertEquals('TST', $value);
    }

    /**
     * Test Country->toArray()
     *
     * @covers Rcm\Entity\Country::toArray
     * @covers Rcm\Entity\Country::setId
     * @covers Rcm\Entity\Country::setName
     * @covers Rcm\Entity\Country::setIso2
     * @covers Rcm\Entity\Country::setIso3
     * @covers Rcm\Entity\Country::setSpecialApiCountry
     * @covers Rcm\Entity\Country::setOldWebCountry
     */
    public function testToArray()
    {
        $expectedArray = array(
            'countryId' => '5',
            'countryName' => 'Mexico',
            'iso2' => 'MX',
            'iso3' => 'MEX',
        );

        $this->country->setId($expectedArray['countryId']);
        $this->country->setName($expectedArray['countryName']);
        $this->country->setIso2($expectedArray['iso2']);
        $this->country->setIso3($expectedArray['iso3']);


        $this->country->setOldWebCountry($expectedArray['oldWebCountry']);

        $actual = $this->country->toArray();

        $this->assertEquals($expectedArray, $actual);
    }

}

