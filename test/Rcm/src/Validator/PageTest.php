<?php
/**
 * Unit Test for the Page Validator
 *
 * This file contains the unit test for the Page Validator
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
 * Unit Test for the Page Validator
 *
 * Unit Test for the Page Validator
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
    protected $pageRepo;

    /** @var \Rcm\Validator\Page */
    protected $validator;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $pageRepo = $this->getMockBuilder('\Rcm\Repository\Page')
            ->disableOriginalConstructor()
            ->getMock();

        $this->pageRepo = $pageRepo;

        /** @var \Rcm\Repository\Page $pageRepo */
        $this->validator = new Page($pageRepo);
        $this->validator->setSiteId(1);
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
        $reflectedProp = $reflectedClass->getProperty('pageType');
        $reflectedProp->setAccessible(true);
        $defaultValue = $reflectedProp->getValue($this->validator);

        $this->assertEquals('n', $defaultValue);

        $this->validator->setPageType('z');

        $result = $reflectedProp->getValue($this->validator);

        $this->assertEquals('z', $result);
    }

    /**
     * Test Set Page Type
     *
     * @return void
     *
     * @covers \Rcm\Validator\Page::setSiteId
     */
    public function testSetSiteId()
    {
        $reflectedClass = new \ReflectionClass($this->validator);
        $reflectedProp = $reflectedClass->getProperty('siteId');
        $reflectedProp->setAccessible(true);
        $defaultValue = $reflectedProp->getValue($this->validator);

        $this->assertEquals(1, $defaultValue);

        $this->validator->setSiteId(22);

        $result = $reflectedProp->getValue($this->validator);

        $this->assertEquals(22, $result);
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

        $this->pageRepo->expects($this->once())
            ->method('findOneBy')
            ->with(
                $this->equalTo([
                        'name' => $pageName,
                        'pageType' => $pageType,
                        'site' => 1
                    ])
            )
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

        $this->pageRepo->expects($this->once())
            ->method('findOneBy')
            ->with(
                $this->equalTo([
                        'name' => $pageName,
                        'pageType' => $pageType,
                        'site' => 1
                    ])
            )
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

        $this->pageRepo->expects($this->never())
            ->method('findOneBy');

        $this->validator->setPageType($pageType);

        $result = $this->validator->isValid($pageName);

        $this->assertFalse($result);

        $messages = $this->validator->getMessages();

        $this->assertNotEmpty($messages);

        $errors = array_keys($messages);

        $this->assertEquals('pageName', $errors[0]);
    }
}