<?php
/**
 * Unit Test for the Domain Entity
 *
 * This file contains the unit test for the Domain Entity
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

use Doctrine\Common\Collections\ArrayCollection;
use Rcm\Entity\Domain;
use Rcm\Entity\Language;
use Rcm\Entity\Site;

/**
 * Unit Test for the Domain Entity
 *
 * Unit Test for the Domain Entity
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class DomainTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Rcm\Entity\Domain */
    protected $domain;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->domain = new Domain();
    }

    /**
     * Test Set Domain Name Validator and it gets called.
     *
     * @return void
     *
     * @covers \Rcm\Entity\Domain
     */
    public function testSetValidatorAndValidatorCalled()
    {
        $mockValidator = $this->getMockBuilder('\Zend\Validator\Hostname')
            ->disableOriginalConstructor()
            ->getMock();

        $mockValidator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        /** @var \Zend\Validator\Hostname $mockValidator */
        $this->domain->setDomainValidator($mockValidator);

        $this->domain->setDomainName('reliv.com');
    }

    /**
     * Test is Primary Domain
     *
     * @return void
     *
     * @covers \Rcm\Entity\Domain
     */
    public function testIsPrimaryDomain()
    {
        $this->assertTrue($this->domain->isPrimary());
    }

    /**
     * Test is Not Primary Domain
     *
     * @return void
     *
     * @covers \Rcm\Entity\Domain
     */
    public function testIsNotPrimaryDomain()
    {
        $primaryDomain = new Domain();
        $this->domain->setPrimary($primaryDomain);

        $this->assertFalse($this->domain->isPrimary());
    }

    /**
     * Test is Get and Set Domain Id
     *
     * @return void
     *
     * @covers \Rcm\Entity\Domain
     */
    public function testGetAndSetDomainId()
    {
        $id = 56;

        $this->domain->setDomainId($id);
        $actual = $this->domain->getDomainId();

        $this->assertEquals($id, $actual);
    }

    /**
     * Test is Get and Set Domain Name
     *
     * @return void
     *
     * @covers \Rcm\Entity\Domain
     */
    public function testGetAndSetDomainName()
    {
        $domainName = 'reliv.com';

        $this->domain->setDomainName($domainName);
        $actual = $this->domain->getDomainName();

        $this->assertEquals($domainName, $actual);
    }

    public function testGetAndSetSite()
    {
        $site = new Site();

        $this->domain->setSite($site);
        $actual = $this->domain->getSite();

        $this->assertEquals($site, $actual);
    }

    /**
     * Test is Get and Set Domain Name When Domain Does not exist yet
     *
     * @return void
     *
     * @covers \Rcm\Entity\Domain
     */
    public function testGetAndSetDomainNameWhenDoesNotExistYet()
    {
        $domainName = 'thisDomainShouldNeverExistAnywhere.com';

        $this->domain->setDomainName($domainName);
        $actual = $this->domain->getDomainName();

        $this->assertEquals($domainName, $actual);
    }

    /**
     * Test is Get and Set Domain Name And Allow for IP's
     *
     * @return void
     *
     * @covers \Rcm\Entity\Domain
     */
    public function testGetAndSetDomainNameWithIpAddress()
    {
        $domainName = '50.112.185.104';

        $this->domain->setDomainName($domainName);
        $actual = $this->domain->getDomainName();

        $this->assertEquals($domainName, $actual);
    }

    /**
     * Test is Set Domain Name with InValid Name
     *
     * @return void
     *
     * @covers \Rcm\Entity\Domain
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testSetDomainNameWithInvalidName()
    {
        $domainName = '^notarealdomain';

        $this->domain->setDomainName($domainName);
    }

    /**
     * Test is Get and Set Primary Domain Name
     *
     * @return void
     *
     * @covers \Rcm\Entity\Domain
     */
    public function testGetAndSetPrimaryDomainName()
    {
        $primaryDomain = new Domain();
        $primaryDomain->setDomainId(654);

        $this->domain->setPrimary($primaryDomain);
        $actual = $this->domain->getPrimary();

        $this->assertEquals($primaryDomain, $actual);
    }

    /**
     * Test is Set Primary Domain Name only accepts Domain Objects
     *
     * @return void
     *
     * @covers \Rcm\Entity\Domain
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testSetPrimaryDomainNameAcceptsOnlyDomains()
    {
        $this->domain->setPrimary(time());
    }

    /**
     * Test is Get and Set Additional Domain Names
     *
     * @return void
     *
     * @covers \Rcm\Entity\Domain
     */
    public function testGetAndSetAdditionalDomainNames()
    {
        $domainOne = new Domain();
        $domainOne->setDomainId(1);

        $domainTwo = new Domain();
        $domainTwo->setDomainId(2);

        $domainThree = new Domain();
        $domainThree->setDomainId(3);

        $expected = array(
            $domainOne,
            $domainTwo,
            $domainThree
        );

        $this->domain->setAdditionalDomain($domainOne);
        $this->domain->setAdditionalDomain($domainTwo);
        $this->domain->setAdditionalDomain($domainThree);

        $actual = $this->domain->getAdditionalDomains();

        $this->assertTrue($actual instanceof ArrayCollection);
        $this->assertEquals($expected, $actual->toArray());
    }

    public function testUtilities(){

        $data = array();
        $data['domainId'] = 123;
        $data['domain'] = 'TEST';
        $data['primaryDomain'] = new Domain();

        $objOne = new Domain();

        $objOne->populate($data);

        $this->assertEquals($data['domainId'], $objOne->getDomainId());
        $this->assertEquals($data['domain'], $objOne->getDomainName());
        $this->assertEquals($data['primaryDomain'], $objOne->getPrimary());

        $objTwo = new Domain();

        $objTwo->populateFromObject($objOne);

        $this->assertEquals($objOne->getDomainId(), $objTwo->getDomainId());
        $this->assertEquals($objOne->getDomainName(), $objTwo->getDomainName());
        $this->assertEquals($objOne->getPrimary(), $objTwo->getPrimary());

        $json = json_encode($objTwo);

        $this->assertJson($json);

        $iterator = $objTwo->getIterator();

        $this->assertInstanceOf('\ArrayIterator', $iterator);

        $array = $objTwo->toArray();

        $this->assertEquals($data['domainId'], $array['domainId']);
        $this->assertEquals($data['domain'], $array['domain']);
        $this->assertEquals($data['primaryDomain'], $array['primaryDomain']);
    }
}
 