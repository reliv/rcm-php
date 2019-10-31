<?php

namespace RcmAdmin\Factory;

use Rcm\Api\Repository\Page\FindPage;
use Rcm\Api\Repository\Page\FindRevisionList;
use Rcm\Entity\Page;
use RcmUser\Api\GetPsrRequest;
use Zend\Http\Request;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface as Router;
use Zend\Navigation\Service\AbstractNavigationFactory;
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
    /** @var \Rcm\Entity\Site */
    protected $currentSite;

    /**
     * @var FindPage
     */
    protected $findPage;

    /**
     * @var FindRevisionList
     */
    protected $findRevisionList;

    /** @var  \Rcm\Entity\Page */
    protected $page = null;

    protected $pageRevision = null;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return \Zend\Navigation\Navigation
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->currentSite = $serviceLocator->get(\Rcm\Service\CurrentSite::class);

        $config = $serviceLocator->get('config');

        $this->findPage = $serviceLocator->get(FindPage::class);

        $this->findRevisionList = $serviceLocator->get(FindRevisionList::class);

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
            $this->page = $this->findPage->__invoke(
                $this->currentSite->getSiteId(),
                $pageMatch,
                $pageTypeMatch
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
     * @param array $pages
     * @param RouteMatch|null $routeMatch
     * @param Router|null $router
     * @param null $request
     *
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
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
        }

        return true;
    }

    /**
     * @param $page
     *
     * @return void
     * @throws \Doctrine\ORM\NonUniqueResultException
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
     * @param $page
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
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
     * @return void
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
     * @param bool $published
     * @param int $limit
     *
     * @return array|mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function getRevisionList(
        $published = false,
        $limit = 10
    ) {
        $revisions = $this->findRevisionList->__invoke(
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
