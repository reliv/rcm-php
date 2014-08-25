<?php
/**
 * SiteManagerTest.php
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmTest\Service
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmTest\Service;

require_once __DIR__ . '/../../../autoload.php';
use Rcm\Entity\PluginInstance;
use Rcm\Entity\Site;
use Rcm\Service\SiteManager;
use Zend\Cache\StorageFactory;
use Zend\Http\PhpEnvironment\Request;


/**
 * SiteManagerTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmTest\Service
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    Rcm\Service\SiteManager
 */
class SiteManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testListAvailableSiteWidePlugins()
    {
        $domainManager = $this->getMockBuilder('Rcm\Service\DomainManager')
            ->disableOriginalConstructor()
            ->getMock();
        $siteRepo = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->setMethods(['findOneBy','isValidSiteId'])
            ->disableOriginalConstructor()
            ->getMock();
        $site = new Site();
        $plugin = new PluginInstance();
        $plugin->setDisplayName('bloomberg');
        $plugin->setSiteWide(true);
        $site->addSiteWidePlugin($plugin);
        $siteRepo->expects($this->any())->method('findOneBy')
            ->will($this->returnValue($site));
        $siteRepo->expects($this->any())->method('isValidSiteId')
            ->will($this->returnValue(true));

        /** @var \Zend\Cache\Storage\Adapter\Memory $cache */
        $cache = StorageFactory::factory(
            array(
                'adapter' => array(
                    'name' => 'Memory',
                    'options' => array(),
                ),
                'plugins' => array(),
            )
        );
        $this->cache = $cache;
        $this->cache->flush();

        $request = new Request();
        $unit = new SiteManager($domainManager, $siteRepo, $cache, $request);
        $unit->setCurrentSiteId(1);
        $plugins = $unit->listAvailableSiteWidePlugins();
        $this->assertEquals(
            'bloomberg',
            array_pop($plugins)['displayName']
        );
    }
} 