<?php


namespace RcmAdmin\Entity;

require_once __DIR__ . '/../autoload.php';

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

        $unit = new SiteApiResponse();

        $data = $unit->toArray();

        $this->assertEquals(count($expected), count($data));

        foreach ($expected as $key => $value) {
            $this->assertTrue(array_key_exists($key, $data), "Missing data key: {$key}");
        }
    }
}
