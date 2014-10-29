<?php
/**
 * Unit Test for the PageName Validator
 *
 * This file contains the unit test for the PageName Validator
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

use Rcm\Validator\PageName;

require_once __DIR__ . '/../../../autoload.php';

/**
 * Unit Test for the PageName Validator
 *
 * Unit Test for the PageName Validator
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class PageNameTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Rcm\Validator\Page */
    protected $validator;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->validator = new PageName();
    }

    /**
     * Test Constructor
     *
     * @return void
     *
     * @covers \Rcm\Validator\PageName::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf('Rcm\Validator\PageName', $this->validator);
    }

    /**
     * Test Is Valid
     *
     * @return void
     *
     * @covers \Rcm\Validator\PageName::isValid
     */
    public function testIsValid()
    {
        $pageName = 'test-page';

        $result = $this->validator->isValid($pageName);

        $this->assertTrue($result);

        $this->assertEmpty($this->validator->getMessages());
    }

    /**
     * Test Is Invalid when page name has spaces
     *
     * @return void
     *
     * @covers \Rcm\Validator\PageName::isValid
     */
    public function testIsValidWhenPageNameContainsSpaces()
    {
        $pageName = 'test page';

        $result = $this->validator->isValid($pageName);

        $this->assertFalse($result);

        $messages = $this->validator->getMessages();

        $this->assertNotEmpty($messages);

        $errors = array_keys($messages);

        $this->assertEquals('pageName', $errors[0]);
    }

    /**
     * Test Is Valid Invalid when page name contains special char
     *
     * @return void
     *
     * @covers \Rcm\Validator\PageName::isValid
     */
    public function testIsValidWhenPageNameContainsSpecialChars()
    {
        $pageName = 'test-page@';

        $result = $this->validator->isValid($pageName);

        $this->assertFalse($result);

        $messages = $this->validator->getMessages();

        $this->assertNotEmpty($messages);

        $errors = array_keys($messages);

        $this->assertEquals('pageName', $errors[0]);
    }

    /**
     * Test Is Valid Invalid when page name contains non english char
     *
     * @return void
     *
     * @covers \Rcm\Validator\PageName::isValid
     */
    public function testIsValidWhenPageNameContainsNonEnglishChars()
    {
        $pageName = 'relìv';

        $result = $this->validator->isValid($pageName);

        $this->assertFalse($result);

        $messages = $this->validator->getMessages();

        $this->assertNotEmpty($messages);

        $errors = array_keys($messages);

        $this->assertEquals('pageName', $errors[0]);
    }
}