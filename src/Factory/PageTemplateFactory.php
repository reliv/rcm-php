<?php

namespace Rcm\Factory;

use Rcm\Validator\PageTemplate;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Rcm PageTemplate Validator
 *
 * Factory for PageTemplate.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 */
class PageTemplateFactory
{
    /**
     * Creates Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     *
     * @return PageTemplate
     */
    public function __invoke($serviceLocator)
    {
        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $serviceLocator->get('\Rcm\Service\CurrentSite');

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository(\Rcm\Entity\Page::class);

        $pageValidator = new PageTemplate($currentSite, $pageRepo);

        return $pageValidator;
    }
}
