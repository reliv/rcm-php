<?php

namespace Rcm\Controller;

use Rcm\Acl\AclActions;
use Rcm\Acl\NotAllowedException;
use Rcm\Http\NotAllowedResponseJsonZf2;
use Rcm\Page\PageTypes\PageTypes;
use Rcm\RequestContext\RequestContext;
use Rcm\SecureRepo\PageSecureRepo;
use Rcm\Service\CurrentSite;
use Zend\View\Model\JsonModel;

class PageCheckController extends AbstractRestfulController
{
    /**
     * Check the page is valid and return a json response
     *
     * @return JsonModel
     */
    public function getList()
    {
        $currentSite = $this->serviceLocator->get(CurrentSite::class);
        $pageSecureRepo = $this->serviceLocator->get(RequestContext::class)->get(PageSecureRepo::class);
        try {
            $pageSecureRepo->assertIsAllowed(
                AclActions::READ,
                ['siteId' => $currentSite->getSiteId()]
            );
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }

        $pageType = $this->params('pageType', PageTypes::NORMAL);
        $pageId = $this->params('pageId', null);

        /** @var \Rcm\Validator\Page $validator */
        $validator = clone($this->getServiceLocator()->get(\Rcm\Validator\Page::class));
        $validator->setPageType($pageType);

        $return = [
            'valid' => true
        ];

        if (!$validator->isValid($pageId)) {
            $return['valid'] = false;
            $return['error'] = $validator->getMessages();

            /** @var \Zend\Http\Response $response */
            $response = $this->response;

            $errorCodes = array_keys($return['error']);

            foreach ($errorCodes as &$errorCode) {
                if ($errorCode == $validator::PAGE_EXISTS) {
                    $response->setStatusCode(409);
                    break;
                } elseif ($errorCode == $validator::PAGE_NAME) {
                    $response->setStatusCode(417);
                }
            }
        }

        return new JsonModel($return);
    }
}
