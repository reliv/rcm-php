<?php
/**
 * test
 */
namespace Rcm\Controller;

class PageSearchApiController extends \Rcm\Controller\BaseController
{
    function siteTitleSearchAction()
    {

        $query = $this->getEvent()->getRouteMatch()->getParam('query');
        $siteId = $this->siteInfo->getSiteId();

        $results = $this->entityMgr->createQuery(
            '
                        select page.name, pageRevision.pageTitle, page.pageType from Rcm\\Entity\\PageRevision pageRevision
                        join pageRevision.page page
                        join page.site site
                        where (page.name like :query or pageRevision.pageTitle like :query) and site.siteId like :siteId
                    '
        )->setParameter('query', '%' . $query . '%')
            ->setParameter('siteId', '%' . $siteId . '%')
            ->getResult();

        $pageNames = array();
        foreach ($results as $result) {

            $pageNames[$result['name']] = array(
                'title' => $result['pageTitle'],
                'url' => $this->getPageUrl($result['name'], $result['pageType'])
            );
        }

        return new \Zend\View\Model\JsonModel($pageNames);
    }

    function allSitePagesAction()
    {
        $pages = $this->siteInfo->getPages();

        /**@var \Rcm\Entity\Page $page */
        foreach ($pages as $page) {

            if ($page->isTemplate()) {
                continue;
            }

            $pageName = $page->getName();

            $pageUrl = $this->getPageUrl($pageName, $page->getPageType());

            $return[$pageUrl] = $pageName;

        }

        asort($return);

        return new \Zend\View\Model\JsonModel($return);
    }


}