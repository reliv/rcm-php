<?php

namespace RcmAdmin\Factory;

use Interop\Container\ContainerInterface;
use RcmAdmin\Form\CreateTemplateFromPageForm;
use Zend\Form\FormElementManager;
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
class CreateTemplateFromPageFormFactory
{
    /**
     * __invoke
     *
     * @param $container ContainerInterface|ServiceLocatorInterface|FormElementManager
     *
     * @return CreateTemplateFromPageForm
     */
    public function __invoke($container)
    {
        // @BC for ZendFramework
        if ($container instanceof FormElementManager) {
            $container = $container->getServiceLocator();
        }

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $container->get('Doctrine\ORM\EntityManager');

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository(\Rcm\Entity\Page::class);

        /** @var \Rcm\Validator\Page $pageValidator */
        $pageValidator = $container->get(\Rcm\Validator\Page::class);

        return new CreateTemplateFromPageForm(
            $pageRepo,
            $pageValidator
        );
    }
}
