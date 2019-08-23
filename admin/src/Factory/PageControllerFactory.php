<?php

namespace RcmAdmin\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Rcm\Entity\Revision;
use Rcm\ImmutableHistory\Page\PageContentFactory;
use Rcm\ImmutableHistory\Page\RcmPageNameToPathname;
use Rcm\RequestContext\RequestContext;
use Rcm\Service\CurrentSite;
use RcmAdmin\Controller\PageController;
use Rcm\SecureRepo\PageSecureRepo;
use RcmUser\Service\RcmUserService;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Admin Page Controller
 *
 * Factory for the Admin Page Controller.
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 */
class PageControllerFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface|ControllerManager
     *
     * @return PageController
     */
    public function __invoke($container)
    {
        // @BC for ZendFramework
        if ($container instanceof ControllerManager) {
            $container = $container->getServiceLocator();
        }

        return new PageController(
            $container->get(RequestContext::class)->get(PageSecureRepo::class),
            $container->get(CurrentSite::class)
        );
    }
}
