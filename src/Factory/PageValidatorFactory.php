<?php

namespace Rcm\Factory;

use Rcm\Validator\Page;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Rcm Page Validator
 *
 * Factory for Rcm Cache.
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
class PageValidatorFactory
{
    /**
     * Creates Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     *
     * @return Page
     */
    public function __invoke($serviceLocator)
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository(\Rcm\Entity\Page::class);

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $serviceLocator->get(\Rcm\Service\CurrentSite::class);

        $pageValidator = new Page($pageRepo);
        $pageValidator->setSiteId($currentSite->getSiteId());

        return $pageValidator;
    }
}
