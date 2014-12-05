<?php
/**
 * Test for Factory AclResourceProviderFactory
 *
 * This file contains the test for the AclResourceProviderFactory.
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

namespace RcmTest\Factory;

require_once __DIR__ . '/../../../autoload.php';

use Rcm\Acl\ResourceProvider;
use Rcm\Factory\AclResourceProviderFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory AclResourceProviderFactory
 *
 * Test for Factory AclResourceProviderFactory
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 */
class AclResourceProviderFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\AclResourceProviderFactory
     */
    public function testCreateService()
    {
        $config = [
            'Rcm' => [
                'Acl' => [
                    'Sites' => [
                        'resourceId' => 'Sites',
                        'parentResourceId' => null,
                        'privileges' => [
                            'read',
                            'edit',
                            'create',
                            'delete',
                            'theme',
                            'admin',
                        ],
                        'name' => 'Sites',
                        'description' => 'Global resource for sites',
                    ],
                    'Pages' => [
                        'resourceId' => 'Pages',
                        'parentResourceId' => null,
                        'privileges' => [
                            'read',
                            'edit',
                            'create',
                            'delete',
                            'copy',
                            'approve',
                            'layout',
                        ],
                        'name' => 'Pages',
                        'description' => 'Global resource for pages',
                    ],
                ]
            ],
        ];

        $mockEntityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteRepo = $this->getMockBuilder('\Rcm\Repository\Site')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($mockSiteRepo));

        $sm = new ServiceManager();
        $sm->setService('config', $config);
        $sm->setService('Doctrine\ORM\EntityManager', $mockEntityManager);

        $factory = new AclResourceProviderFactory();
        $object = $factory->createService($sm);

        $this->assertTrue($object instanceof ResourceProvider);
    }

    /**
     * Generic test for the constructor with no config defined
     *
     * @return null
     * @covers \Rcm\Factory\AclResourceProviderFactory
     */
    public function testCreateServiceNoConfig()
    {
        $config = [];

        $mockEntityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteRepo = $this->getMockBuilder('\Rcm\Repository\Site')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($mockSiteRepo));

        $sm = new ServiceManager();
        $sm->setService('config', $config);
        $sm->setService('Doctrine\ORM\EntityManager', $mockEntityManager);

        $factory = new AclResourceProviderFactory();
        $object = $factory->createService($sm);

        $this->assertTrue($object instanceof ResourceProvider);
    }
}