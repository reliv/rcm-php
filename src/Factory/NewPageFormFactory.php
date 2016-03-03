<?php

namespace RcmAdmin\Factory;

use RcmAdmin\Form\NewPageForm;
use Zend\Di\ServiceLocator;
use Zend\ServiceManager\FactoryInterface;
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
class NewPageFormFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $formElementManager Zend Controler Manager
     *
     * @return NewPageForm
     */
    public function createService(ServiceLocatorInterface $formElementManager)
    {
        /** @var \Zend\Form\FormElementManager $formElementMgr For IDE */
        $formElementMgr = $formElementManager;

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $formElementMgr->getServiceLocator();

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $serviceLocator->get('Rcm\Service\CurrentSite');

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository('\Rcm\Entity\Page');

        /** @var \Rcm\Service\LayoutManager $layoutManager */
        $layoutManager = $serviceLocator->get('Rcm\Service\LayoutManager');

        /** @var \Rcm\Validator\MainLayout $layoutValidator */
        $layoutValidator = $serviceLocator->get('Rcm\Validator\MainLayout');

        /** @var \Rcm\Validator\Page $pageValidator */
        $pageValidator = $serviceLocator->get('Rcm\Validator\Page');

        /** @var \Rcm\Validator\PageTemplate $templateValidator */
        $templateValidator = $serviceLocator->get('Rcm\Validator\PageTemplate');

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
