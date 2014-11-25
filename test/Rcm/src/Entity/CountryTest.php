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

use Rcm\Entity\Country;

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
class CountryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Rcm\Entity\Country */
    protected $country;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->country = new Country();
    }

    /**
     * Test Get and Set Country Name
     *
     * @return void
     *
     * @covers \Rcm\Entity\Country
     */
    public function testGetAndSetCountryName()
    {
        $countryName = 'United States';

        $this->country->setCountryName($countryName);

        $actual = $this->country->getCountryName();

        $this->assertEquals($countryName, $actual);
    }

    /**
     * Test Get and Set ISO2
     *
     * @return void
     *
     * @covers \Rcm\Entity\Country
     */
    public function testGetAndSetIso2()
    {
        $iso2 = 'US';

        $this->country->setIso2($iso2);

        $actual = $this->country->getIso2();

        $this->assertEquals($iso2, $actual);
    }

    /**
     * Test Set ISO2 with too many characters
     *
     * @return void
     *
     * @covers \Rcm\Entity\Country
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testSetIso2TooLong()
    {
        $iso2 = 'USA';
        $this->country->setIso2($iso2);
    }

    /**
     * Test Set ISO2 with not enough characters
     *
     * @return void
     *
     * @covers \Rcm\Entity\Country
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testSetIso2TooShort()
    {
        $iso2 = 'U';
        $this->country->setIso2($iso2);
    }

    /**
     * Test Get and Set ISO3
     *
     * @return void
     *
     * @covers \Rcm\Entity\Country
     */
    public function testGetAndSetIso3()
    {
        $iso3 = 'USA';

        $this->country->setIso3($iso3);

        $actual = $this->country->getIso3();

        $this->assertEquals($iso3, $actual);
    }

    /**
     * Test Set ISO3 with too many characters
     *
     * @return void
     *
     * @covers \Rcm\Entity\Country
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testSetIso3TooLong()
    {
        $iso3 = 'United States';
        $this->country->setIso3($iso3);
    }

    /**
     * Test Set ISO3 with not enough characters
     *
     * @return void
     *
     * @covers \Rcm\Entity\Country
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testSetIso3TooShort()
    {
        $iso3 = 'US';
        $this->country->setIso3($iso3);
    }


    public function testUtilities()
    {
        $data = array();
        $data['iso3'] = 'TST';
        $data['iso2'] = 'TS';
        $data['countryName'] = 'TEST';

        $newCountry = new Country();

        $newCountry->populate($data);

        $this->assertEquals($data['iso3'], $newCountry->getIso3());
        $this->assertEquals($data['iso2'], $newCountry->getIso2());
        $this->assertEquals($data['countryName'], $newCountry->getCountryName());

        $secCountry = new Country();

        $secCountry->populateFromObject($newCountry);

        $this->assertEquals($newCountry->getIso3(), $secCountry->getIso3());
        $this->assertEquals($newCountry->getIso2(), $secCountry->getIso2());
        $this->assertEquals($newCountry->getCountryName(), $secCountry->getCountryName());

        $json = json_encode($secCountry);

        $this->assertJson($json);

        $iterator = $secCountry->getIterator();

        $this->assertInstanceOf('\ArrayIterator', $iterator);

        $array = $secCountry->toArray();

        $this->assertEquals($data['iso3'], $array['iso3']);
        $this->assertEquals($data['iso2'], $array['iso2']);
        $this->assertEquals($data['countryName'], $array['countryName']);
    }
}
 