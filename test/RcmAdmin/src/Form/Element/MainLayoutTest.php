<?php
/**
 * Unit Test for Form Element Main Layout
 *
 * This file contains the unit test for Form Element Main Layout
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

namespace RcmAdminTest\Form\Element;

use RcmAdmin\Form\Element\MainLayout;

require_once __DIR__ . '/../../../../autoload.php';

/**
 * Unit Test for Form Element Main Layout
 *
 * Unit Test for Form Element Main Layout
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class MainLayoutTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test setOptions
     *
     * @return void
     *
     * @covers \RcmAdmin\Form\Element\MainLayout::setOptions
     */
    public function testSetOptions()
    {
        $options['layouts'] = [
            'option1' => ['display' => 'value1'],
            'option2' => ['display' => 'value2']
        ];

        $element = new MainLayout();
        $element->setOptions($options);

        $valueOptions = $element->getValueOptions();

        $expected = [
            'option1' => 'value1',
            'option2' => 'value2'
        ];

        $this->assertEquals($expected, $valueOptions);
    }
}
