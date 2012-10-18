<?php
/**
 * Test PWS Info Database Entity
 *
 * Test Suite for the Doctorine 2 definition file for PWS Info Objects.
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
require_once __DIR__ . '/../../src/Rcm/Entity/PwsInfo.php';
require_once __DIR__ . '/../../src/Rcm/Entity/Site.php';

use Rcm\Entity\PwsInfo;
use Rcm\Entity\Site;

/**
 * Tests for the PWS Info Database Entity
 *
 * This test suite tests the Doctorine 2 definition file for PWS Info 
 * Objects.
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
class PwsInfoTest extends PHPUnit_Framework_TestCase
{
    
    /**
     *
     * @var PwsInfo
     */
    private $PwsInfo;
    
    /**
     * @var array Array for the known dataset to use for testing
     */
    private $pwsDataset;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->PwsInfo=new PwsInfo();
        
        $this->pwsDataset = array(
            'pwsId' => 7,
            'site' => new \Rcm\Entity\Site(),
            'activeDate' => new \DateTime('2012-07-08 11:14:15'),
            'cancelDate' => new \DateTime('2010-07-08 11:14:15'),
            'lastUpdated' => new \DateTime('2011-07-08 11:14:15'),
        );
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->PwsInfo=null;
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
    }

    /**
     * Tests PwsInfo->setPwsId() & PwsInfo->getPwsId()
     *
     * @covers Rcm\Entity\PwsInfo::setPwsId
     * @covers Rcm\Entity\PwsInfo::getPwsId
     */
    public function testGetAndSetPwsId()
    {
        $this->PwsInfo->setPwsId($this->pwsDataset['pwsId']);
        $actual = $this->PwsInfo->getPwsId();

        $this->assertEquals($this->pwsDataset['pwsId'], $actual);
    }

    /**
     * Tests PwsInfo->setSite() & PwsInfo->getSite()
     *
     * @covers Rcm\Entity\PwsInfo::setSite
     * @covers Rcm\Entity\PwsInfo::getSite
     */
    public function testGetAndSetSite()
    {
        $this->PwsInfo->setSite(new \Rcm\Entity\Site());

        $this->assertInstanceOf(
            '\Rcm\Entity\Site',
            $this->PwsInfo->getSite()
        );

    }

    /**
     * Tests PwsInfo->setActiveDate() & PwsInfo->getActiveDate()
     *
     * @covers Rcm\Entity\PwsInfo::setActiveDate
     * @covers Rcm\Entity\PwsInfo::getActiveDate
     */
    public function testGetAndSetActiveDate()
    {
        $this->PwsInfo->setActiveDate($this->pwsDataset['activeDate']);
        $actual = $this->PwsInfo->getActiveDate();

        $this->assertEquals($this->pwsDataset['activeDate'], $actual);
    }

    /**
     * Tests PwsInfo->setCancelDate() & PwsInfo->getCancelDate()
     *
     * @covers Rcm\Entity\PwsInfo::setCancelDate
     * @covers Rcm\Entity\PwsInfo::getCancelDate
     */
    public function testGetAndSetCancelDate()
    {
        $this->PwsInfo->setCancelDate($this->pwsDataset['cancelDate']);
        $actual = $this->PwsInfo->getCancelDate();

        $this->assertEquals($this->pwsDataset['cancelDate'], $actual);
    }

    /**
     * Tests PwsInfo->setLastUpdated() & PwsInfo->getLastUpdated()
     *
     * @covers Rcm\Entity\PwsInfo::setLastUpdated
     * @covers Rcm\Entity\PwsInfo::getLastUpdated
     */
    public function testGetAndSetLastUpdatedDate()
    {
        $this->PwsInfo->setLastUpdated($this->pwsDataset['lastUpdated']);
        $actual = $this->PwsInfo->getLastUpdated();

        $this->assertEquals($this->pwsDataset['lastUpdated'], $actual);
    }

    /**
     * Tests PwsInfo->toArray()
     *
     * @covers Rcm\Entity\PwsInfo::toArray
     */
    public function testToArray()
    {
        $this->setPWSdata();
        
        $actual = $this->PwsInfo->toArray();
        
        $this->assertEquals($this->pwsDataset, $actual);
    }

    /**
     * Set the initial data to use for tests
     */
    private function setPWSdata()
    {        
        $this->PwsInfo->setPwsId($this->pwsDataset['pwsId']);
        $this->PwsInfo->setSite($this->pwsDataset['site']);
        $this->PwsInfo->setActiveDate($this->pwsDataset['activeDate']);
        $this->PwsInfo->setCancelDate($this->pwsDataset['cancelDate']);
        $this->PwsInfo->setLastUpdated($this->pwsDataset['lastUpdated']);
    }
}

