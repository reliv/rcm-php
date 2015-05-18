<?php
/**
 * Unit Test for View Helper DisplayErrors
 *
 * This file contains the unit test for View Helper DisplayErrors
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace RcmAdminTest\View\Helper;

use RcmAdmin\View\Helper\DisplayErrors;

require_once __DIR__ . '/../../../../autoload.php';

/**
 * Unit Test for View Helper DisplayErrors
 *
 * Unit Test for View Helper DisplayErrors
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class DisplayErrorsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test ErrorMapper
     *
     * @return void
     *
     * @covers \RcmAdmin\View\Helper\DisplayErrors::errorMapper
     */
    public function testErrorMapper()
    {
        $errors = [
            [
                'errorCode' => 'pageName',
                'errorMessage' => 'pageName Error',
                'expected' =>
                    '<p class="urlErrorMessage">pageName Error</p>' . "\n"
            ],
            [
                'errorCode' => 'pageExists',
                'errorMessage' => 'pageExists Error',
                'expected' =>
                    '<p class="urlErrorMessage">pageExists Error</p>' . "\n"
            ],
            [
                'errorCode' => 'unknown',
                'errorMessage' => 'unknown Error',
                'expected' => '<p class="errorMessage">unknown Error</p>' . "\n"
            ],
        ];

        $helper = new DisplayErrors();

        foreach ($errors as $error) {
            $result = $helper->errorMapper(
                $error['errorCode'],
                $error['errorMessage']
            );

            $this->assertEquals($error['expected'], $result);
        }
    }

    /**
     * Test renderErrors
     *
     * @return void
     *
     * @covers \RcmAdmin\View\Helper\DisplayErrors::renderErrors
     */
    public function testRenderErrors()
    {
        $this->renderErrorsTest();
    }

    /**
     * Test renderErrors with no errors passed in
     *
     * @return void
     *
     * @covers \RcmAdmin\View\Helper\DisplayErrors::renderErrors
     */
    public function testRenderErrorsWithNoErrorsPassedIn()
    {
        $this->renderErrorsWithNoErrorsTest();
    }

    /**
     * Test invoke
     *
     * @return void
     *
     * @covers \RcmAdmin\View\Helper\DisplayErrors::__invoke
     */
    public function testInvoke()
    {
        $this->renderErrorsTest(true);
    }

    /**
     * Test invoke with no errors passed in
     *
     * @return void
     *
     * @covers \RcmAdmin\View\Helper\DisplayErrors::__invoke
     */
    public function testInvokeWithNoErrorsPassedIn()
    {
        $this->renderErrorsWithNoErrorsTest(true);
    }

    /**
     * Tests rendering Errors.
     *
     * @param bool $useInvoke Use invoke instead of using the actual method
     *
     * @return void
     */
    protected function renderErrorsTest($useInvoke = false)
    {
        $errors = [
            ['pageName' => 'pageName Error'],
            ['pageExists' => 'pageExists Error'],
            ['unknown' => 'unknown Error'],
        ];

        $messages = [
            '<p class="urlErrorMessage">pageName Error</p>' . "\n",
            '<p class="urlErrorMessage">pageExists Error</p>' . "\n",
            '<p class="errorMessage">unknown Error</p>' . "\n"
        ];

        $helper = new DisplayErrors();
        if ($useInvoke) {
            $result = $helper($errors);
        } else {
            $result = $helper->renderErrors($errors);
        }

        foreach ($messages as $expected) {
            $this->assertContains($expected, $result);
        }
    }

    /**
     * Tests rendering Errors.
     *
     * @param bool $useInvoke Use invoke instead of using the actual method
     *
     * @return void
     */
    protected function renderErrorsWithNoErrorsTest($useInvoke = false)
    {
        $errors = [];

        $helper = new DisplayErrors();

        if ($useInvoke) {
            $result = $helper($errors);
        } else {
            $result = $helper->renderErrors($errors);
        }

        $this->assertNull($result);
    }
}