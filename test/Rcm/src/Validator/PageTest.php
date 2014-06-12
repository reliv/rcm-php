<?php
/**
 * Unit Test for the Plugin Manager Service
 *
 * This file contains the unit test for the Plugin Manager Service
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

use Rcm\Validator\Page;

require_once __DIR__ . '/../../../autoload.php';

/**
 * Unit Test for the Plugin Manager Service
 *
 * Unit Test for the Plugin Manager Service
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class PageTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $pageManager;

    /** @var \Rcm\Validator\Page */
    protected $validator;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $pageManager = $this->getMockBuilder('\Rcm\Service\PageManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->pageManager = $pageManager;

        /** @var \Rcm\Service\PageManager $pageManager */
        $this->validator = new Page($pageManager);
    }

    /**
     * Test Constructor
     *
     * @return void
     *
     * @covers \Rcm\Validator\Page::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf('Rcm\Validator\Page', $this->validator);
    }

    /**
     * Test Set Page Type
     *
     * @return void
     *
     * @covers \Rcm\Validator\Page::setPageType
     */
    public function testSetPageType()
    {
        $reflectedClass = new \ReflectionClass($this->validator);
        $relfectedProp = $reflectedClass->getProperty('pageType');
        $relfectedProp->setAccessible(true);
        $defaultValue = $relfectedProp->getValue($this->validator);

        $this->assertEquals('n', $defaultValue);

        $this->validator->setPageType('z');

        $result = $relfectedProp->getValue($this->validator);

        $this->assertEquals('z', $result);
    }

    /**
     * Test Is Valid
     *
     * @return void
     *
     * @covers \Rcm\Validator\Page::isValid
     */
    public function testIsValid()
    {
        $pageName = 'test-page';
        $pageType = 'z';

        $this->pageManager->expects($this->once())
            ->method('getPageByName')
            ->with($pageName, $pageType)
            ->will($this->returnValue(false));

        $this->validator->setPageType($pageType);

        $result = $this->validator->isValid($pageName);

        $this->assertTrue($result);

        $this->assertEmpty($this->validator->getMessages());
    }

    /**
     * Test Is Valid when page exists
     *
     * @return void
     *
     * @covers \Rcm\Validator\Page::isValid
     */
    public function testIsValidWhenPageExists()
    {
        $pageName = 'test-page';
        $pageType = 'z';

        $this->pageManager->expects($this->once())
            ->method('getPageByName')
            ->with($pageName, $pageType)
            ->will($this->returnValue(true));

        $this->validator->setPageType($pageType);

        $result = $this->validator->isValid($pageName);

        $this->assertFalse($result);

        $messages = $this->validator->getMessages();

        $this->assertNotEmpty($messages);

        $errors = array_keys($messages);

        $this->assertEquals('pageExists', $errors[0]);
    }

    /**
     * Test Is Valid when page exists
     *
     * @return void
     *
     * @covers \Rcm\Validator\Page::isValid
     */
    public function testIsValidWhenPageNameInvalid()
    {
        $pageName = 'test page';
        $pageType = 'z';

        $this->pageManager->expects($this->never())
            ->method('getPageByName');

        $this->validator->setPageType($pageType);

        $result = $this->validator->isValid($pageName);

        $this->assertFalse($result);

        $messages = $this->validator->getMessages();

        $this->assertNotEmpty($messages);

        $errors = array_keys($messages);

        $this->assertEquals('pageName', $errors[0]);
    }
}