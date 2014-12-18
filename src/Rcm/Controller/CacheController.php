<?php
/**
 * Index Controller for the entire application
 *
 * This file contains the main controller used for the application.  This
 * should extend from the base class and should need no further modification.
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

use Rcm\Entity\Revision;
use Rcm\Entity\Site;
use Rcm\Repository\Page as PageRepo;
use Rcm\Entity\Page;
use Rcm\Service\LayoutManager;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Index Controller for the entire application
 *
 * This is main controller used for the application.  This should extend from
 * the base class located in Rcm and should need no further
 * modification.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @method Response redirectToPage($pageName, $pageType) Redirect to CMS
 *                                                                  Page
 *
 * @method boolean rcmIsAllowed($resource, $action) Is User Allowed
 * @method boolean shouldShowRevisions($siteId, $pageName, $pageType = 'n') Should Show Revisions for pages
 * @method boolean rcmIsSiteAdmin() Is user a CMS admin
 * @method boolean rcmIsPageAllowed(Page $page) Is user allowed to view a page
 */
class CacheController extends AbstractActionController
{
    /**
     * Index Action.  Main action for page in the CMS.
     *
     * @return ViewModel
     */
    public function flushAction()
    {
        /** @var \Zend\Cache\Storage\Adapter\Memory $cache */
        $cache = $this->serviceLocator->get('Rcm\Service\Cache');
        $cache->flush();
    }
}
