<?php

namespace Rcm\Controller;
use Rcm\Controller\EntityMgrAwareController;


class StateApiController extends EntityMgrAwareController
{
    /**
     * Returns geo codes as JSON. 404's if zip code not found.
     */
    function listStatesAction()
    {
        $countryIso3 = $this->getEvent()->getRouteMatch()->getParam('country');
        $stateEntities = $this->entityMgr->getRepository('\Rcm\Entity\States')
            ->findBy
            (array('country', $countryIso3));

        $states=array();
        foreach($stateEntities as $state){
            $states[$state->getState]=$state->getName();
        }

        if (!count($states)) {
            $httpCode = '404 Not Found';
            header('HTTP/1.0 ' . $httpCode);
            exit($httpCode);
        }

        exit(json_encode($states));
    }
}

