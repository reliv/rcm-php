<?php
/**
 * Unit Test for the Redirect Entity
 *
 * This file contains the unit test for the Redirect Entity
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

use Rcm\Entity\Redirect;
use Rcm\Entity\Site;

/**
 * Unit Test for the Redirect Entity
 *
 * Unit Test for the Redirect Entity
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class RedirectTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Rcm\Entity\Redirect */
    protected $redirect;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->redirect = new Redirect();
    }

    /**
     * Test Set URL Validator and test that it gets called.
     *
     * @return void
     *
     * @covers \Rcm\Entity\Redirect
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
        $this->redirect->setUrlValidator($mockValidator);

        $this->redirect->setRedirectUrl('reliv.com');
    }

    /**
     * Test Get and Set Redirect Id
     *
     * @return void
     *
     * @covers \Rcm\Entity\Redirect
     */
    public function testGetAndSetRedirectId()
    {
        $id = 4;

        $this->redirect->setRedirectId($id);

        $actual = $this->redirect->getRedirectId();

        $this->assertEquals($id, $actual);
    }

    /**
     * Test Get and Set Redirect Url
     *
     * @return void
     *
     * @covers \Rcm\Entity\Redirect
     */
    public function testGetAndSetRedirectUrl()
    {
        $url = '/some-page';

        $this->redirect->setRedirectUrl($url);

        $actual = $this->redirect->getRedirectUrl();

        $this->assertEquals($url, $actual);
    }

    /**
     * Test Set Redirect Url with invalid URL
     *
     * @return void
     *
     * @covers \Rcm\Entity\Redirect
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testSetRedirectUrlWithInvalidUrl()
    {
        $url = '//';

        $this->redirect->setRedirectUrl($url);
    }

    /**
     * Test Get and Set Request Url
     *
     * @return void
     *
     * @covers \Rcm\Entity\Redirect
     */
    public function testGetAndSetRequestUrl()
    {
        $url = '/some-page';

        $this->redirect->setRequestUrl($url);

        $actual = $this->redirect->getRequestUrl();

        $this->assertEquals($url, $actual);
    }

    /**
     * Test Set Request Url with invalid URL
     *
     * @return void
     *
     * @covers \Rcm\Entity\Redirect
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testSetRequestUrlWithInvalidUrl()
    {
        $url = '//';

        $this->redirect->setRequestUrl($url);
    }

    /**
     * Test Get and Set Site For Redirect
     *
     * @return void
     *
     * @covers \Rcm\Entity\Redirect
     */
    public function testGetAndSetSite()
    {
        $site = new Site();
        $site->setSiteId(28);

        $this->redirect->setSite($site);

        $actual = $this->redirect->getSite();

        $this->assertEquals($site, $actual);
    }

    /**
     * Test Set Site Only Accepts a Site object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Redirect
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testSetSiteOnlyAcceptsSite()
    {
        $this->redirect->setSite(time());
    }
}
 