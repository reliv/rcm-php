<?php
/**
 * Unit Test for View Helper FormPageLayout
 *
 * This file contains the unit test for View Helper FormPageLayout
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

use RcmAdmin\View\Helper\FormPageLayout;
use Zend\Form\Element\Url;

require_once __DIR__ . '/../../../../autoload.php';

/**
 * Unit Test for View Helper FormPageLayout
 *
 * Unit Test for View Helper FormPageLayout
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class FormPageLayoutTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getInputType
     *
     * @return void
     *
     * @covers \RcmAdmin\View\Helper\FormPageLayout::getInputType
     */
    public function testGetInputType()
    {
        $helper = new FormPageLayout();

        $reflectedClass = new \ReflectionClass($helper);

        $reflectedMethod = $reflectedClass->getMethod('getInputType');
        $reflectedMethod->setAccessible(true);

        $result = $reflectedMethod->invoke($helper);

        $this->assertEquals('radio', $result);
    }

    /**
     * Test getName
     *
     * @return void
     *
     * @covers \RcmAdmin\View\Helper\FormPageLayout::getName
     */
    public function testGetName()
    {
        $name = 'some-name';

        $mockElement = $this->getMockBuilder('RcmAdmin\Form\Element\MainLayout')
            ->disableOriginalConstructor()
            ->getMock();

        $mockElement->expects($this->once())
            ->method('getName')
            ->will($this->returnValue($name));

        $helper = new FormPageLayout();

        $reflectedClass = new \ReflectionClass($helper);

        $reflectedMethod = $reflectedClass->getMethod('getName');
        $reflectedMethod->setAccessible(true);

        $result = $reflectedMethod->invoke($helper, $mockElement);

        $this->assertEquals($name, $result);
    }

    /**
     * Test render
     *
     * @return void
     *
     * @covers \RcmAdmin\View\Helper\FormPageLayout::render
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testRender()
    {
        $options = [
            'layouts' => [
                'testOne' => [
                    'screenShot' => 'testOne.jpg',
                    'display' => 'testOne Display'
                ],
                'testTwo' => [
                    'screenShot' => 'testTwo.jpg',
                    'display' => 'testTwo Display'
                ],
            ],
        ];

        $expectedImages = [
            'testOne.jpg',
            'testTwo.jpg'
        ];

        $expectedText = [
            'testOne Display',
            'testTwo Display'
        ];

        $mockElement = $this->getMockBuilder('RcmAdmin\Form\Element\MainLayout')
            ->setMethods(
                [
                    'getOptions'
                ]
            )->getMock();

        $mockElement->expects($this->once())
            ->method('getOptions')
            ->will($this->returnValue($options));

        $helper = new FormPageLayout();

        $result = $helper->render($mockElement);

        $this->assertEquals(
            2,
            substr_count($result, '<span class="pageLayoutLabel">')
        );

        $doc = new \DOMDocument();
        $doc->loadHTML($result);

        $xpath = new \DOMXPath($doc);

        $wrappers = $xpath->query("//span[@class='pageLayoutLabel']");

        $this->assertEquals(
            2,
            $wrappers->length
        );

        /** @var \DomElement $wrapper */
        foreach ($wrappers as $wrapper) {
            /** @var \DomElement $img */
            $img = $wrapper->getElementsByTagName('img')
                ->item(0)
                ->getAttribute('src');

            $this->assertNotEmpty($img);

            $this->assertTrue(
                in_array(
                    $img,
                    $expectedImages
                )
            );

            $textNode = $xpath
                ->query("span[@class='pageLayoutTextDisplay']", $wrapper);

            $this->assertEquals(1, $textNode->length);

            $text = trim($textNode->item(0)->nodeValue);

            $this->assertNotEmpty($text);

            $this->assertTrue(
                in_array(
                    $text,
                    $expectedText
                )
            );

            $imageOverlay = $xpath
                ->query("span[@class='pageLayoutImageOverlay']", $wrapper);

            $this->assertEquals(1, $imageOverlay->length);

            $value = trim($imageOverlay->item(0)->nodeValue);

            $this->assertEmpty($value);
        }
    }

    /**
     * Test render only accepts PageLayout elements
     *
     * @return void
     *
     * @covers \RcmAdmin\View\Helper\FormPageLayout::render
     * @expectedException \Zend\Form\Exception\InvalidArgumentException
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testRenderOnlyAcceptsPageLayoutElements()
    {
        $helper = new FormPageLayout();
        $helper->render(new Url());
    }
}