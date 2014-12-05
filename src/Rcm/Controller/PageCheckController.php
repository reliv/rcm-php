<?php
/**
 * Page Check API Controller
 *
 * This file contains the Page Check controller used for the application.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */
namespace Rcm\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Model\JsonModel;

/**
 * Page Check API Controller
 *
 * Page Check API Controller.  This API will validate
 * a page name and is generally used by the admin screens
 * to make sure the page name is valid and does not currently
 * exist.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class PageCheckController extends AbstractRestfulController implements ServiceLocatorAwareInterface
{
    /**
     * Check the page is valid and return a json response
     *
     * @return JsonModel
     */
    public function getList()
    {
        $pageType = $this->params('pageType', 'n');
        $pageId = $this->params('pageId', null);

        /** @var \Rcm\Validator\Page $validator */
        $validator = $this->getServiceLocator()->get('Rcm\Validator\Page');
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
