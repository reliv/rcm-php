<?php
/**
 * Unit Test for the MainLayout Validator
 *
 * This file contains the unit test for the MainLayout Validator
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

namespace RcmTest\Validator;

use Rcm\Entity\Site;
use Rcm\Validator\MainLayout;

require_once __DIR__ . '/../../../autoload.php';

/**
 * Unit Test for the MainLayout Validator
 *
 * Unit Test for the MainLayout Validator
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class MainLayoutTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $layoutManager;

    /** @var \Rcm\Validator\MainLayout */
    protected $validator;

    protected $currentSite;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $layoutManager = $this->getMockBuilder('\Rcm\Service\LayoutManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->currentSite = new Site();
        $this->currentSite->setSiteId(1);

        $this->layoutManager = $layoutManager;

        /** @var \Rcm\Service\LayoutManager $layoutManager */
        $this->validator = new MainLayout($this->currentSite, $layoutManager);
    }

    /**
     * Test Constructor
     *
     * @return void
     *
     * @covers \Rcm\Validator\MainLayout::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf('Rcm\Validator\MainLayout', $this->validator);
    }

    /**
     * Test Is Valid
     *
     * @return void
     *
     * @covers \Rcm\Validator\MainLayout::isValid
     */
    public function testIsValid()
    {
        $layout = 'some-layout';

        $this->layoutManager->expects($this->once())
            ->method('isLayoutValid')
            ->with($this->equalTo($this->currentSite), $this->equalTo($layout))
            ->will($this->returnValue(true));

        $result = $this->validator->isValid($layout);

        $this->assertTrue($result);

        $this->assertEmpty($this->validator->getMessages());
    }

    /**
     * Test Is Valid when page exists
     *
     * @return void
     *
     * @covers \Rcm\Validator\MainLayout::isValid
     */
    public function testIsValidWhenLayoutIsInvalid()
    {
        $layout = 'some-layout';

        $this->layoutManager->expects($this->once())
            ->method('isLayoutValid')
            ->with($this->equalTo($this->currentSite), $this->equalTo($layout))
            ->will($this->returnValue(false));

        $result = $this->validator->isValid($layout);

        $this->assertFalse($result);

        $messages = $this->validator->getMessages();

        $this->assertNotEmpty($messages);

        $errors = array_keys($messages);

        $this->assertEquals('pageTemplate', $errors[0]);
    }
}