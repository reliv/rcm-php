<?php

namespace Rcm\Controller;

use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Cache Controller for the application
 *
 * Currently this controller just allows a person to flush the entire cache
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
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
