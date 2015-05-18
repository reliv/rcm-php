<?php
/**
 * Service Factory for the Create Template From Page Form
 *
 * This file contains the factory needed to generate a Create Template From Page Form.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace RcmAdmin\Factory;

use RcmAdmin\Form\CreateTemplateFromPageForm;
use RcmAdmin\Form\NewPageForm;
use Zend\Di\ServiceLocator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Create Template From Page Form
 *
 * Factory for the Create Template From Page Form.
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
class CreateTemplateFromPageFormFactory implements FactoryInterface
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

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository('\Rcm\Entity\Page');

        /** @var \Rcm\Validator\Page $pageValidator */
        $pageValidator = $serviceLocator->get('Rcm\Validator\Page');

        return new CreateTemplateFromPageForm(
            $pageRepo,
            $pageValidator
        );
    }
}
