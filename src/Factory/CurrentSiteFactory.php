<?php

namespace Rcm\Factory;

use Rcm\Entity\Site;
use Rcm\Tracking\Model\Tracking;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\Ip;

/**
 * Service Helper for the current site
 *
 * Service Helper for the current site
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
class CurrentSiteFactory implements FactoryInterface
{
    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend View Helper Mgr
     *
     * @return Site
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
//        //Uncomment to turn on sql query logging. They echo in the browser
//        $serviceLocator->get('Doctrine\ORM\EntityManager')
//            ->getConnection()
//            ->getConfiguration()
//            ->setSQLLogger(new DoctrineQueryLoggerWithTime());

        /** @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $serviceLocator->get('request');

        if ($request instanceof \Zend\Console\Request) {
            // Fake Site for console
            return new Site(
                Tracking::UNKNOWN_USER_ID,
                'Fake site for console in ' . get_class($this)
            );
        }

        $serverParam = $request->getServer();
        $hostParts = explode(':', $serverParam->get('HTTP_HOST'));
        $currentDomain = $hostParts[0];

        //Use the default site if the requested domain name is an IP address
        $ipValidator = new Ip();
        if ($ipValidator->isValid($currentDomain)) {
            $config = $serviceLocator->get('Config');
            $currentDomain = $config['Rcm']['defaultDomain'];
        }

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository(\Rcm\Entity\Site::class);

        $currentSite = $siteRepo->getSiteByDomain($currentDomain);

        if (empty($currentSite)) {
            $currentSite = new Site(
                Tracking::UNKNOWN_USER_ID,
                'Fake site due to site domain not found ' . get_class($this)
            );
        }

        return $currentSite;
    }
}
