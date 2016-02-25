<?php

namespace RcmAdmin\Factory;

use Rcm\Entity\Page;
use Zend\Navigation\Service\AbstractNavigationFactory;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface as Router;
use Zend\Http\Request;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Admin Navigation Container
 *
 * Factory for the Admin Navigation Container
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class AdminNavigationFactory extends AbstractNavigationFactory
{
    /** @var  \RcmUser\Service\RcmUserService */
    protected $rcmUserService;

    /** @var \Rcm\Entity\Site */
    protected $currentSite;

    /** @var  \Rcm\Repository\Page */
    protected $pageRepo;

    /** @var  \Rcm\Entity\Page */
    protected $page = null;

    /** @var  \Rcm\Acl\CmsPermissionChecks */
    protected $cmsPermissionChecks;

    protected $pageRevision = null;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return \Zend\Navigation\Navigation
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->rcmUserService = $serviceLocator->get(
            'RcmUser\Service\RcmUserService'
        );
        $this->cmsPermissionChecks = $serviceLocator->get(
            'Rcm\Acl\CmsPermissionsChecks'
        );
        $this->currentSite = $serviceLocator->get('Rcm\Service\CurrentSite');

        $config = $serviceLocator->get('config');

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Rcm\Repository\Page $pageRepo */
        $this->pageRepo = $entityManager->getRepository('\Rcm\Entity\Page');

        $application = $serviceLocator->get('Application');

        /** @var RouteMatch $routeMatch */
        $routeMatch = $application->getMvcEvent()->getRouteMatch();

        if (!in_array(
            $routeMatch->getMatchedRouteName(),
            $config['Rcm']['RcmCmsPageRouteNames']
        )
        ) {
            return parent::createService($serviceLocator);
        }

        $pageMatch = $routeMatch->getParam('page', 'index');

        if ($pageMatch instanceof Page) {
            $pageMatch = $pageMatch->getName();
        }

        $this->pageRevision = $routeMatch->getParam('revision', null);
        $pageTypeMatch = $routeMatch->getParam('pageType', 'n');

        if (!empty($pageMatch)) {
            $this->page = $this->pageRepo->findOneBy(
                [
                    'name' => $pageMatch,
                    'pageType' => $pageTypeMatch
                ]
            );
        }

        return parent::createService($serviceLocator);
    }


    /**
     * Get the name of the navigation container
     *
     * @return string
     */
    protected function getName()
    {
        $name = 'RcmAdminMenu';

        return $name;
    }

    /**
     * Zend Inject Components.
     *
     * @param array        $pages
     * @param RouteMatch   $routeMatch
     * @param Router       $router
     * @param null|Request $request
     *
     * @return mixed
     */
    protected function injectComponents(
        array $pages,
        RouteMatch $routeMatch = null,
        Router $router = null,
        $request = null
    ) {
        foreach ($pages as $key => &$page) {
            if (!$this->shouldShowInNavigation($page)) {
                unset($pages[$key]);
                continue;
            }

            $this->setupRcmNavigation($page);

            $hasUri = isset($page['uri']);
            $hasMvc = isset($page['action']) || isset($page['controller'])
                || isset($page['route']);
            if ($hasMvc) {
                if (!isset($page['routeMatch']) && $routeMatch) {
                    $page['routeMatch'] = $routeMatch;
                }
                if (!isset($page['router'])) {
                    $page['router'] = $router;
                }
            } elseif ($hasUri) {
                if (!isset($page['request'])) {
                    $page['request'] = $request;
                }
            }

            if (isset($page['pages'])) {
                $page['pages'] = $this->injectComponents(
                    $page['pages'],
                    $routeMatch,
                    $router,
                    $request
                );
            }
        }

        return $pages;
    }

    /**
     * Should link be shown in nav bar?
     *
     * @param $page
     *
     * @return bool
     */
    protected function shouldShowInNavigation(&$page)
    {
        if (isset($page['rcmOnly'])
            && $page['rcmOnly']
            && empty($this->page)
        ) {
            return false;
        }

        if (isset($page['acl']) && is_array($page['acl'])
            && !empty($page['acl']['resource'])
        ) {
            $providerId = null;
            if (!empty($page['acl']['providerId'])) {
                $providerId = $page['acl']['providerId'];
            }

            $privilege = null;
            if (!empty($page['acl']['privilege'])) {
                $privilege = $page['acl']['privilege'];
            }

            $resource = $page['acl']['resource'];

            $resource = str_replace(
                [
                    ':siteId',
                    ':pageName'
                ],
                [
                    $this->currentSite->getSiteId(),
                    $this->page->getName()
                ],
                $resource
            );

            if (!empty($this->page)) {
                $resource = str_replace(
                    [
                        ':siteId',
                        ':pageName'
                    ],
                    [
                        $this->currentSite->getSiteId(),
                        $this->page->getName()
                    ],
                    $resource
                );
            } else {
                $resource = str_replace(
                    [':siteId'],
                    [$this->currentSite->getSiteId()],
                    $resource
                );
            }

            if (!$this->rcmUserService->isAllowed(
                $resource,
                $privilege,
                $providerId
            )
            ) {
                return false;
            }

        }

        return true;
    }

    /**
     * Setup Rcm Navigation
     *
     * @param $page
     */
    protected function setupRcmNavigation(&$page)
    {
        if (empty($this->page)) {
            return;
        }

        if (isset($page['params']) && is_array($page['params'])) {
            array_walk(
                $page['params'],
                [
                    $this,
                    'updatePlaceHolders'
                ],
                $this->pageRevision
            );
        }

        $this->buildRevisionsNav($page);
    }

    /**
     * buildRevisionsNav
     *
     * @param $page
     *
     * @return mixed
     */
    protected function buildRevisionsNav(&$page)
    {
        if (empty($page['rcmIncludeRevisions'])) {
            return $page;
        }

        if (empty($page['pages'])) {
            $page['pages'] = [];
        }

        foreach ($page['rcmIncludeRevisions'] as $config) {
            $published = (bool)$config['published'];
            $limit = (int)$config['limit'];

            $revisions = $this->getRevisionList($published, $limit);

            if (empty($revisions['revisions'])) {
                continue;
            }

            foreach ($revisions['revisions'] as $revision) {
                $pageConfig = $config['page'];

                array_walk_recursive(
                    $pageConfig,
                    [
                        $this,
                        'parseRevisionConfigValue'
                    ],
                    $revision
                );

                $page['pages'][] = $pageConfig;
            }
        }

        return $page;
    }

    /**
     * parseRevisionConfigValue
     *
     * @param $value
     * @param $key
     * @param $revision
     *
     * @return mixed
     */
    protected function parseRevisionConfigValue(&$value, $key, $revision)
    {
        $find = [
            ':revisionCreatedDate',
            ':revisionPublishedDate',
            ':revisionAuthor',
            ':revisionId',
        ];

        if (!empty($revision['publishedDate'])) {
            $revision['publishedDate'] = $revision['publishedDate']->format("r");
        }

        $replace = [
            $revision['createdDate']->format("r"),
            $revision['publishedDate'],
            $revision['author'],
            $revision['revisionId']
        ];

        $value = str_replace($find, $replace, $value);

        $this->updatePlaceHolders($value, $key, $revision['revisionId']);
    }

    /**
     * Get Draft Revision List
     *
     * @return array
     */
    /**
     * getRevisionList
     *
     * @param bool $published
     * @param int  $limit
     *
     * @return array|mixed
     */
    protected function getRevisionList(
        $published = false,
        $limit = 10
    ) {
        $revisions =  $this->pageRepo->getRevisionList(
            $this->currentSite->getSiteId(),
            $this->page->getName(),
            $this->page->getPageType(),
            $published,
            $limit
        );

        return $revisions;
    }

    /**
     * Update config place holders with correct data
     *
     * @param $item
     * @param $key
     * @param $revisionNumber
     *
     * @return void
     */
    protected function updatePlaceHolders(&$item, $key, $revisionNumber)
    {

        if (empty($this->page)) {
            return;
        }

        $find = [
            ':rcmPageName',
            ':rcmPageType',
            ':rcmPageRevision'
        ];

        $replace = [
            $this->page->getName(),
            $this->page->getPageType(),
            $revisionNumber,
        ];

        $item = str_replace($find, $replace, $item);
    }
}
