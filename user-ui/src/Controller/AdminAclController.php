<?php

namespace RcmUser\Ui\Controller;

use RcmUser\Provider\RcmUserAclResourceProvider;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AdminAclController extends AbstractAdminController
{
    /**
     * @return mixed|\Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_ACL
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        $viewArr = [];

        return $this->buildView($viewArr);
    }
}
