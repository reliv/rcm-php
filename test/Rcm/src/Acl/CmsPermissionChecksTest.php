<?php


namespace Rcm\Test\Acl;

use Rcm\Acl\CmsPermissionChecks;
use Rcm\Test\Mocks;

require_once __DIR__ . '/../../../Mocks.php';

class CmsPermissionChecksTest extends Mocks
{
    /*
     *
     */
    public $testCases
        = array(
            '_DEFAULT' => array(
                'rcmUserService' => array(),

                'authorizeService' => array(
                    'isAllowedMap' => array(
                        array(
                            'sites.123.pages.PAGE_TYPE.PAGE_NAME',
                            'read',
                            'Rcm\Acl\ResourceProvider',
                            true
                        ),
                        array(
                            'sites.123',
                            'admin',
                            'Rcm\Acl\ResourceProvider',
                            true
                        ),
                        array(
                            'sites.123.pages.PAGE_TYPE.PAGE_NAME',
                            'edit',
                            'Rcm\Acl\ResourceProvider',
                            true
                        ),
                        array(
                            'sites.123.pages.PAGE_TYPE.PAGE_NAME',
                            'approve',
                            'Rcm\Acl\ResourceProvider',
                            true
                        ),
                        array(
                            'sites.123.pages.PAGE_TYPE.PAGE_NAME',
                            'revisions',
                            'Rcm\Acl\ResourceProvider',
                            true
                        ),
                        array(
                            'sites.123.pages',
                            'create',
                            'Rcm\Acl\ResourceProvider',
                            true
                        ),
                        array(
                            'NOPE',
                            'NOPE',
                            'Rcm\Acl\ResourceProvider',
                            false
                        ),
                    )
                ),

                'aclDataService' => array(
                    'rules' => array(
                        1 => 1,
                        2 => 2,
                    ),
                ),

                'page' => array(
                    'name' => 'PAGE_NAME',
                    'type' => 'PAGE_TYPE',
                ),

                'site' => array(
                    'siteId' => 123,
                    'loginPage' => '/LOGIN_PAGE',
                    'notAuthorizedPage' => '/NOT_AUTHED_PAGE',
                    'notFoundPage' => '/NOT_FOUND_PAGE',
                ),
            ),
            'NO_RULES' => array(
                'rcmUserService' => array(),

                'authorizeService' => array(
                    'isAllowedMap' => array(
                        array(
                            'sites.123.pages.PAGE_TYPE.PAGE_NAME',
                            'read',
                            'Rcm\Acl\ResourceProvider',
                            true
                        ),
                        array(
                            'sites.123',
                            'admin',
                            'Rcm\Acl\ResourceProvider',
                            true
                        ),
                        array(
                            'sites.123.pages.PAGE_TYPE.PAGE_NAME',
                            'edit',
                            'Rcm\Acl\ResourceProvider',
                            true
                        ),
                        array(
                            'sites.123.pages.PAGE_TYPE.PAGE_NAME',
                            'approve',
                            'Rcm\Acl\ResourceProvider',
                            true
                        ),
                        array(
                            'sites.123.pages.PAGE_TYPE.PAGE_NAME',
                            'revisions',
                            'Rcm\Acl\ResourceProvider',
                            true
                        ),
                        array(
                            'sites.123.pages',
                            'create',
                            'Rcm\Acl\ResourceProvider',
                            true
                        ),
                        array(
                            'NOPE',
                            'NOPE',
                            'Rcm\Acl\ResourceProvider',
                            false
                        ),
                    )
                ),

                'aclDataService' => array(
                    'rules' => array(),
                ),

                'page' => array(
                    'name' => 'PAGE_NAME',
                    'type' => 'PAGE_TYPE',
                ),

                'site' => array(
                    'siteId' => 123,
                    'loginPage' => '/LOGIN_PAGE',
                    'notAuthorizedPage' => '/NOT_AUTHED_PAGE',
                    'notFoundPage' => '/NOT_FOUND_PAGE',
                ),
            )
        );

    public function getTestCase($testCaseKey = '_DEFAULT')
    {
        return $this->testCases[$testCaseKey];
    }

    /*
     *
     */
    public function getMockRcmUserService($testCaseKey = '_DEFAULT')
    {
        $testCase = $this->getTestCase($testCaseKey);

        $rcmUserService = $this->getMockBuilder(
            '\RcmUser\Service\RcmUserService'
        )
            ->disableOriginalConstructor()
            ->getMock();


        $rcmUserService->expects($this->any())
            ->method('getAuthorizeService')
            ->will(
                $this->returnValue($this->getMockAuthorizeService($testCaseKey))
            );


        $rcmUserService->expects($this->any())
            ->method('isAllowed')
            ->will(
                $this->returnValueMap($testCase['authorizeService']['isAllowedMap'])
            );


        return $rcmUserService;
    }

    /*
     *
     */
    public function getMockAuthorizeService($testCaseKey = '_DEFAULT')
    {
        $testCase = $this->getTestCase($testCaseKey);

        $authorizeService = $this->getMockBuilder(
            '\RcmUser\Acl\Service\AuthorizeService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $authorizeService->expects($this->any())
            ->method('isAllowed')
            ->will(
                $this->returnValueMap($testCase['authorizeService']['isAllowedMap'])
            );

        $authorizeService->expects($this->any())
            ->method('getAclDataService')
            ->will($this->returnValue($this->getMockAclDataService($testCaseKey)));

        return $authorizeService;
    }

    public function getMockAclDataService($testCaseKey = '_DEFAULT')
    {
        $testCase = $this->getTestCase($testCaseKey);

        $aclDataService = $this->getMockBuilder(
            'RcmUser\Acl\Service\AclDataService'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $aclDataService->expects($this->any())
            ->method('getRulesByResource')
            ->will($this->returnValue($testCase['aclDataService']['rules']));

        return $aclDataService;
    }

    public function getMockPage($testCaseKey = '_DEFAULT')
    {
        $testCase = $this->getTestCase($testCaseKey);

        $page = $this->getMockBuilder(
            'Rcm\Entity\Page'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $page->expects($this->any())
            ->method('getName')
            ->will(
                $this->returnValue($testCase['page']['name'])
            );

        $page->expects($this->any())
            ->method('getPageType')
            ->will(
                $this->returnValue($testCase['page']['type'])
            );

        $page->expects($this->any())
            ->method('getSite')
            ->will(
                $this->returnValue($this->getMockSite($testCaseKey))
            );

        return $page;
    }

    public function getMockSite($testCaseKey = '_DEFAULT')
    {
        $testCase = $this->getTestCase($testCaseKey);

        $site = $this->getMockBuilder(
            'Rcm\Entity\Site'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $site->expects($this->any())
            ->method('getSiteId')
            ->will($this->returnValue($testCase['site']['siteId']));

        $site->expects($this->any())
            ->method('getLoginPage')
            ->will($this->returnValue($testCase['site']['loginPage']));

        $site->expects($this->any())
            ->method('getNotAuthorizedPage')
            ->will($this->returnValue($testCase['site']['notAuthorizedPage']));

        $site->expects($this->any())
            ->method('getNotFoundPage')
            ->will($this->returnValue($testCase['site']['notFoundPage']));
        return $site;
    }

    public function getCmsPermissionChecks($testCaseKey = '_DEFAULT')
    {
        return new CmsPermissionChecks($this->getMockRcmUserService($testCaseKey));
    }

    public function testIsPageAllowedForReading()
    {
        $cmsPermissionChecks = $this->getCmsPermissionChecks('_DEFAULT');

        $allowed = $cmsPermissionChecks->isPageAllowedForReading($this->getMockPage('_DEFAULT'));

        $this->assertTrue($allowed);
    }

    public function testSiteAdminCheck()
    {
        $cmsPermissionChecks = $this->getCmsPermissionChecks('_DEFAULT');

        $allowed = $cmsPermissionChecks->siteAdminCheck($this->getMockSite('_DEFAULT'));

        $this->assertTrue($allowed);
    }

    public function testShouldShowRevisions()
    {

        $testCaseKey = '_DEFAULT';
        $cmsPermissionChecks = $this->getCmsPermissionChecks($testCaseKey);

        $testCase = $this->getTestCase($testCaseKey);

        $allowed = $cmsPermissionChecks->shouldShowRevisions(
            $testCase['site']['siteId'],
            $testCase['page']['type'],
            $testCase['page']['name']
        );

        $this->assertTrue($allowed);


    }

    public function XXXtestIsPageRestricted()
    {
        $testCaseKey = '_DEFAULT';
        $cmsPermissionChecks = $this->getCmsPermissionChecks($testCaseKey);

        $testCase = $this->getTestCase($testCaseKey);

        $allowed = $cmsPermissionChecks->isPageRestricted(
            $testCase['site']['siteId'],
            $testCase['page']['type'],
            $testCase['page']['name'],
            'read'
        );

        $this->assertTrue($allowed);

        $allowed = $cmsPermissionChecks->isPageRestricted(
            $testCase['site']['siteId'],
            $testCase['page']['type'],
            $testCase['page']['name'],
            'read'
        );

        $this->assertTrue($allowed);
    }

    public function testBuildSiteResourceId()
    {

    }

    public function testBuildPagesResourceId()
    {

    }

    public function testbuildPageResourceId()
    {

    }
}
 