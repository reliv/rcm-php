<?php
/**
 * Service Factory for the Rcm PageTemplate Validator
 *
 * This file contains the factory needed to validate Rcm PageTemplate.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace Rcm\Factory;

use Rcm\Validator\Page;
use Zend\ServiceManager\FactoryInterface;
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
class PageTemplateFactory implements FactoryInterface
{
    /**
     * Creates Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     *
     * @return Page
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $serviceLocator->get('\Rcm\Service\CurrentSite');

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository('\Rcm\Entity\Page');

        $pageValidator = new \Rcm\Validator\PageTemplate($currentSite, $pageRepo);

        return $pageValidator;
    }
}
