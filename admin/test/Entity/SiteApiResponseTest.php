<?php

namespace RcmAdmin\Entity;

use Rcm\Entity\Country;
use Rcm\Entity\Domain;
use Rcm\Entity\Language;



/**
 * Class SiteApiResponseTest
 *
 * Site API Response Model
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class SiteApiResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testCase
     *
     * @return void
     */
    public function testCase()
    {
        $expected = [
            'siteId' => 'int',
            'domain' => 'string',
            'theme' => 'string',
            'siteLayout' => 'string',
            'siteTitle' => 'string',
            'language' => 'string',
            'country' => 'string',
            'status' => 'string',
            'favIcon' => 'string',
            'loginPage' => 'string',
            'notAuthorizedPage' => 'string',
            'notFoundPage' => 'string',
        ];

        $unit = new SiteApiResponse(
            'user123'
        );

        $unit->setDomain(
            $this->getMockBuilder(
                Domain::class
            )
                ->disableOriginalConstructor()
                ->getMock()
        );

        $unit->setLanguage(
            $this->getMockBuilder(
                Language::class
            )
                ->disableOriginalConstructor()
                ->getMock()
        );

        $unit->setCountry(
            $this->getMockBuilder(
                Country::class
            )
                ->disableOriginalConstructor()
                ->getMock()
        );

        $data = $unit->toArray();

        $this->assertEquals(count($expected), count($data));

        foreach ($expected as $key => $value) {
            $this->assertTrue(array_key_exists($key, $data), "Missing data key: {$key}");
        }
    }
}
