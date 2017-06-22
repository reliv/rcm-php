<?php


namespace RcmAdmin\Entity;

use Rcm\Entity\Page;
use Rcm\Entity\Site;

require_once __DIR__ . '/../autoload.php';

/**
 * Class SitePageApiResponseTest
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
class SitePageApiResponseTest extends \PHPUnit_Framework_TestCase
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
            'pageId' => 'int',
            'name' => 'string',
            'author' => 'string',
            'createdDate' => 'string',
            'lastPublished' => 'string',
            'pageLayout' => 'string',
            'siteLayoutOverride' => 'string',
            'pageTitle' => 'string',
            'description' => 'string',
            'keywords' => 'string',
            'pageType' => 'string',
        ];

        $unit = new SitePageApiResponse(
            new Page('user123')
        );
        $unit->setSite(new Site('user123'));
        $unit->setLastPublished(new \DateTime());

        $data = $unit->toArray();

        $this->assertEquals(count($expected), count($data));

        foreach ($expected as $key => $value) {
            $this->assertTrue(array_key_exists($key, $data), "Missing data key: {$key}");
        }
    }
}
