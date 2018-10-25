<?php

namespace RcmAdmin\Factory;

use Interop\Container\ContainerInterface;
use RcmAdmin\Form\NewPageForm;
use Zend\Form\FormElementManager;
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
class NewPageFormFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface|FormElementManager
     *
     * @return NewPageForm
     */
    public function __invoke($container)
    {
        // @BC for ZendFramework
        if ($container instanceof FormElementManager) {
            $container = $container->getServiceLocator();
        }

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $container->get(\Rcm\Service\CurrentSite::class);

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $container->get('Doctrine\ORM\EntityManager');

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository(\Rcm\Entity\Page::class);

        /** @var \Rcm\Service\LayoutManager $layoutManager */
        $layoutManager = $container->get(\Rcm\Service\LayoutManager::class);

        /** @var \Rcm\Validator\MainLayout $layoutValidator */
        $layoutValidator = $container->get(\Rcm\Validator\MainLayout::class);

        /** @var \Rcm\Validator\Page $pageValidator */
        $pageValidator = $container->get(\Rcm\Validator\Page::class);

        /** @var \Rcm\Validator\PageTemplate $templateValidator */
        $templateValidator = $container->get(\Rcm\Validator\PageTemplate::class);

        return new NewPageForm(
            $currentSite,
            $pageRepo,
            $layoutManager,
            $layoutValidator,
            $pageValidator,
            $templateValidator
        );
    }
}
