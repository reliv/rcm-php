<?php

namespace Rcm\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Http\Response;

class PageSearchApiController extends AbstractRestfulController
{
    function siteTitleSearchAction()
    {
        $query = $this->getEvent()->getRouteMatch()->getParam('query');
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $sm = $this->getServiceLocator()->get(
            'Rcm\Service\SiteManager'
        );
        $siteId = $sm->getCurrentSiteId();

        $results = $em->createQuery(
            '
                        select page.name, page.pageTitle, page.pageType from Rcm\\Entity\\Page page
                        join page.site site
                        where (page.name like :query or page.pageTitle like :query) and site.siteId like :siteId
                    '
        )->setParameter('query', '%' . $query . '%')
            ->setParameter('siteId', '%' . $siteId . '%')
            ->getResult();

        $pageNames = array();
        foreach ($results as $result) {

            $pageNames[$result['name']] = array(
                'title' => $result['pageTitle'],
                'url' => $this->urlToPage($result['name'], $result['pageType'])
            );
        }
        return new JsonModel($pageNames);
    }

    function allSitePagesAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $sm = $this->getServiceLocator()->get(
            'Rcm\Service\SiteManager'
        );
        $siteId = $sm->getCurrentSiteId();

        $site = $em->getRepository(
            '\Rcm\Entity\Site'
        )->findOneBy(
                array(
                    'siteId' => $siteId
                )
            );
        /**
         * @var \Rcm\Entity\Page $pages
         */
        $pages = $site->getPages();
        // print_r($pages); exit;
        // $pages = $this->siteInfo->getPages();

        /**@var \Rcm\Entity\Page $page */
        foreach ($pages as $page) {
            $pageName = $page->getName();
            $pageUrl = $this->urlToPage($pageName, $page->getPageType());
            $return[$pageUrl] = $pageName;
        }
        asort($return);
        return new JsonModel($return);
    }


}