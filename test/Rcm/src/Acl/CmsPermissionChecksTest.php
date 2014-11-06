<?php


namespace Rcm\Test\Acl;

use Rcm\Acl\CmsPermissionChecks;
use Rcm\Test\Mocks;
use RcmUser\Result;

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
                        'data' => array(
                            1 => 1,
                            2 => 2,
                        ),
                        'code' => 1,
                        'message' => 'TEST_MESSAGE'
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
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
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
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
                            'edit',
                            'Rcm\Acl\ResourceProvider',
                            false
                        ),
                        array(
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
                            'approve',
                            'Rcm\Acl\ResourceProvider',
                            true
                        ),
                        array(
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
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
                        'data' => array(),
                        'code' => 1,
                        'message' => 'TEST_MESSAGE'
                    ),
                ),

                'page' => array(
                    'name' => 'NOT_FOUND_PAGE',
                    'type' => 'PAGE_TYPE',
                ),

                'site' => array(
                    'siteId' => 123,
                    'loginPage' => '/LOGIN_PAGE',
                    'notAuthorizedPage' => '/NOT_AUTHED_PAGE',
                    'notFoundPage' => '/NOT_FOUND_PAGE',
                ),
            ),
            'ALLOW_CHECK2' => array(
                'rcmUserService' => array(),

                'authorizeService' => array(
                    'isAllowedMap' => array(
                        array(
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
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
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
                            'edit',
                            'Rcm\Acl\ResourceProvider',
                            false
                        ),
                        array(
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
                            'approve',
                            'Rcm\Acl\ResourceProvider',
                            false
                        ),
                        array(
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
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
                        'data' => array(),
                        'code' => 1,
                        'message' => 'TEST_MESSAGE'
                    ),
                ),

                'page' => array(
                    'name' => 'NOT_FOUND_PAGE',
                    'type' => 'PAGE_TYPE',
                ),

                'site' => array(
                    'siteId' => 123,
                    'loginPage' => '/LOGIN_PAGE',
                    'notAuthorizedPage' => '/NOT_AUTHED_PAGE',
                    'notFoundPage' => '/NOT_FOUND_PAGE',
                ),
            ),
            'ALLOW_CHECK3' => array(
                'rcmUserService' => array(),

                'authorizeService' => array(
                    'isAllowedMap' => array(
                        array(
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
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
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
                            'edit',
                            'Rcm\Acl\ResourceProvider',
                            false
                        ),
                        array(
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
                            'approve',
                            'Rcm\Acl\ResourceProvider',
                            false
                        ),
                        array(
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
                            'revisions',
                            'Rcm\Acl\ResourceProvider',
                            false
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
                        'data' => array(),
                        'code' => 1,
                        'message' => 'TEST_MESSAGE'
                    ),
                ),

                'page' => array(
                    'name' => 'NOT_FOUND_PAGE',
                    'type' => 'PAGE_TYPE',
                ),

                'site' => array(
                    'siteId' => 123,
                    'loginPage' => '/LOGIN_PAGE',
                    'notAuthorizedPage' => '/NOT_AUTHED_PAGE',
                    'notFoundPage' => '/NOT_FOUND_PAGE',
                ),
            ),
            'ALLOW_CHECK4' => array(
                'rcmUserService' => array(),

                'authorizeService' => array(
                    'isAllowedMap' => array(
                        array(
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
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
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
                            'edit',
                            'Rcm\Acl\ResourceProvider',
                            false
                        ),
                        array(
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
                            'approve',
                            'Rcm\Acl\ResourceProvider',
                            false
                        ),
                        array(
                            'sites.123.pages.PAGE_TYPE.NOT_FOUND_PAGE',
                            'revisions',
                            'Rcm\Acl\ResourceProvider',
                            false
                        ),
                        array(
                            'sites.123.pages',
                            'create',
                            'Rcm\Acl\ResourceProvider',
                            false
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
                        'data' => array(),
                        'code' => 1,
                        'message' => 'TEST_MESSAGE'
                    ),
                ),

                'page' => array(
                    'name' => 'NOT_FOUND_PAGE',
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

        $rulesResult = new Result(
            $testCase['aclDataService']['rules']['data'],
            $testCase['aclDataService']['rules']['code'],
            $testCase['aclDataService']['rules']['message']
        );

        $aclDataService->expects($this->any())
            ->method('getRulesByResource')
            ->will($this->returnValue($rulesResult));

        $aclDataService->expects($this->any())
            ->method('getRulesByResourcePrivilege')
            ->will($this->returnValue($rulesResult));

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

    /**
     * testIsPageAllowedForReading
     *
     * @return void
     */
    public function testIsPageAllowedForReading()
    {
        $cmsPermissionChecks = $this->getCmsPermissionChecks('_DEFAULT');

        $allowed = $cmsPermissionChecks->isPageAllowedForReading(
            $this->getMockPage('_DEFAULT')
        );

        $this->assertTrue($allowed);

        $cmsPermissionChecks = $this->getCmsPermissionChecks('NO_RULES');

        $allowed = $cmsPermissionChecks->isPageAllowedForReading(
            $this->getMockPage('NO_RULES')
        );

        $this->assertTrue($allowed);
    }

    public function testSiteAdminCheck()
    {
        $cmsPermissionChecks = $this->getCmsPermissionChecks('_DEFAULT');

        $allowed = $cmsPermissionChecks->siteAdminCheck(
            $this->getMockSite('_DEFAULT')
        );

        $this->assertTrue($allowed);
    }

    public function testShouldShowRevisions()
    {

        //echo "ALLOW_CHECK0\n";
        $testCaseKey = '_DEFAULT';
        $cmsPermissionChecks = $this->getCmsPermissionChecks($testCaseKey);

        $testCase = $this->getTestCase($testCaseKey);

        $allowed = $cmsPermissionChecks->shouldShowRevisions(
            $testCase['site']['siteId'],
            $testCase['page']['type'],
            $testCase['page']['name']
        );

        $this->assertTrue($allowed);

        //echo "ALLOW_CHECK1\n";
        $testCaseKey = 'NO_RULES'; //
        $cmsPermissionChecks = $this->getCmsPermissionChecks($testCaseKey);

        $testCase = $this->getTestCase($testCaseKey);

        $allowed = $cmsPermissionChecks->shouldShowRevisions(
            $testCase['site']['siteId'],
            $testCase['page']['type'],
            $testCase['page']['name']
        );

        $this->assertTrue($allowed);

        //echo "ALLOW_CHECK2\n";
        $testCaseKey = 'ALLOW_CHECK2'; //
        $cmsPermissionChecks = $this->getCmsPermissionChecks($testCaseKey);

        $testCase = $this->getTestCase($testCaseKey);

        $allowed = $cmsPermissionChecks->shouldShowRevisions(
            $testCase['site']['siteId'],
            $testCase['page']['type'],
            $testCase['page']['name']
        );

        $this->assertTrue($allowed);

        //echo "ALLOW_CHECK3\n";
        $testCaseKey = 'ALLOW_CHECK3'; //
        $cmsPermissionChecks = $this->getCmsPermissionChecks($testCaseKey);

        $testCase = $this->getTestCase($testCaseKey);

        $allowed = $cmsPermissionChecks->shouldShowRevisions(
            $testCase['site']['siteId'],
            $testCase['page']['type'],
            $testCase['page']['name']
        );

        $this->assertTrue($allowed);

        //echo "ALLOW_CHECK4\n";
        $testCaseKey = 'ALLOW_CHECK4'; //
        $cmsPermissionChecks = $this->getCmsPermissionChecks($testCaseKey);

        $testCase = $this->getTestCase($testCaseKey);

        $allowed = $cmsPermissionChecks->shouldShowRevisions(
            $testCase['site']['siteId'],
            $testCase['page']['type'],
            $testCase['page']['name']
        );

        $this->assertFalse($allowed);
    }

    public function testIsPageRestricted()
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

        $testCaseKey = 'NO_RULES';
        $cmsPermissionChecks = $this->getCmsPermissionChecks($testCaseKey);

        $testCase = $this->getTestCase($testCaseKey);

        $allowed = $cmsPermissionChecks->isPageRestricted(
            $testCase['site']['siteId'],
            $testCase['page']['type'],
            $testCase['page']['name'],
            'read'
        );

        $this->assertFalse($allowed);
    }

    public function testBuildResourceIds()
    {

        $testCaseKey = '_DEFAULT';
        $cmsPermissionChecks = $this->getCmsPermissionChecks($testCaseKey);

        $testCase = $this->getTestCase($testCaseKey);

        $siteId = $testCase['site']['siteId'];
        $pageType = $testCase['page']['type'];
        $pageName = $testCase['page']['name'];

        $siteResouceId = $cmsPermissionChecks->buildSiteResourceId(
            $siteId
        );

        $this->assertEquals(
            'sites.' . $siteId, $siteResouceId
        );

        $pagesResouceId = $cmsPermissionChecks->buildPagesResourceId(
            $siteId
        );

        $this->assertEquals(
            'sites.' . $siteId . '.pages', $pagesResouceId
        );

        $pageResouceId = $cmsPermissionChecks->buildPageResourceId(
            $siteId,
            $pageType,
            $pageName
        );

        $this->assertEquals(
            'sites.' . $siteId . '.pages.' . $pageType . '.' . $pageName,
            $pageResouceId
        );
    }

    public function testBuildPagesResourceId()
    {

    }

    public function testbuildPageResourceId()
    {

    }
}
 