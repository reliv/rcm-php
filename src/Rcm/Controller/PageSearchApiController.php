<?php

namespace Rcm\Controller;

class PageSearchApiController extends  \Rcm\Controller\BaseController
{
    function siteTitleSearchAction(){

        $query = $this->getEvent()->getRouteMatch()->getParam('query');
        $siteId = $this->getEvent()->getRouteMatch()->getParam('siteId');

// TODO MAKE THIS RESPECT THE PROVIDED SITE ID
//        $results = $this->entityMgr->createQuery('
//            select page.name from Rcm\\Entity\\PageRevision pageRevision
//            join pageRevision.page page
//            /*join page.site site*/
//            where (page.name like :query or pageRevision.pageTitle like :query)
//            /*and site.siteId = :siteId*/
//        ')->setParameter('query', '%'.$query.'%')
//        //->setParameter('siteId', '%'.$siteId.'%')
//            ->getResult();

        $results = $this->entityMgr->createQuery('
            select page.name, pageRevision.pageTitle from Rcm\\Entity\\PageRevision pageRevision
            join pageRevision.page page
            where (page.name like :query or pageRevision.pageTitle like :query)
        ')->setParameter('query', '%'.$query.'%')
            ->getResult();

        $pageNames = array();
        foreach($results as $result){
            $pageNames[$result['name']]=$result['pageTitle'];
        }

        return new \Zend\View\Model\JsonModel($pageNames);

    }
}