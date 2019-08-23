<?php

namespace Rcm\Api\Repository\Site;

use Doctrine\ORM\EntityManager;
use Rcm\Api\Repository\Options;
use Rcm\Entity\Container;
use Rcm\Entity\Domain;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Tracking\Model\Tracking;

/**
 * @deprecated Use SiteSecureRepo instead
 */
class CopySite
{
//    const OPTION_DO_FLUSH = 'doFlush';
//    /**
//     * @var EntityManager
//     */
//    protected $entityManager;
//
//    /**
//     * @var \Rcm\Repository\Site
//     */
//    protected $repository;
//
//    /**
//     * @var \Rcm\Repository\Domain
//     */
//    protected $domainRepository;

//    /**
//     * @param EntityManager $entityManager
//     */
//    public function __construct(
//        EntityManager $entityManager
//    ) {
////        $this->entityManager = $entityManager;
////        $this->repository = $entityManager->getRepository(
////            Site::class
////        );
////        $this->domainRepository = $entityManager->getRepository(
////            Domain::class
////        );
//    }

    /**
     * @deprecated Use SiteSecureRepo instead
     * @param Site $sourceSite
     * @param string $newDomainName
     * @param array $newSiteData
     * @param string $createdByUserId
     * @param string $createdReason
     * @param array $options
     *
     * @return Site
     * @throws \Exception
     */
    public function __invoke(
        Site $sourceSite,
        string $newDomainName,
        array $newSiteData,
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON,
        array $options = []
    ) {

        throw new \Exception(
            'This was disabled during audit project in 2018-11 because'
            . ' didn\'t apear to be in use and doesn\'t follow audit log rules'
        );
//Disabled during audit project in 2018-11 because didn't apear to be in use and doesn't follow audit log rules
//        $doFlush = Options::get(
//            $options,
//            self::OPTION_DO_FLUSH,
//            false
//        );
//
//        $newDomain = $this->domainRepository->createDomain(
//            $newDomainName,
//            $createdByUserId,
//            $createdReason,
//            null,
//            false
//        );
//
//        try {
//            $copySite = $this->copySite(
//                $sourceSite,
//                $newDomain,
//                $createdByUserId,
//                $createdReason,
//                false
//            );
//        } catch (\Exception $exception) {
//            // Remove domain if error occurs
//            $this->entityManager->remove($newDomain);
//            throw $exception;
//        }
//
//        $copySite->populate($newSiteData);
//
//        if ($doFlush) {
//            $this->entityManager->flush($newDomain);
//            $this->entityManager->flush($copySite);
//            // @todo Missing pages publishedRevisions in flush
//            $this->entityManager->flush($copySite->getPages()->toArray());
//            // @todo Missing containers publishedRevisions in flush
//            $this->entityManager->flush($copySite->getContainers()->toArray());
//        }
//
//        return $copySite;
    }
//Disabled during audit project in 2018-11 because didn't apear to be in use and doesn't follow audit log rules
//    /**
//     * @param Site   $sourceSite
//     * @param Domain $domain
//     * @param string $createdByUserId
//     * @param string $createdReason
//     * @param bool   $doFlush
//     *
//     * @return Site
//     */
//    protected function copySite(
//        Site $sourceSite,
//        Domain $domain,
//        string $createdByUserId,
//        string $createdReason,
//        $doFlush = false
//    ) {
//        $entityManager = $this->entityManager;
//
//        $domain->setModifiedByUserId(
//            $createdByUserId,
//            'Copy site modified domain in ' . get_class($this)
//            . ' for: ' . $createdReason
//        );
//
//        $copySite = $sourceSite->newInstance(
//            $createdByUserId,
//            'Copy site in ' . get_class($this)
//            . ' for: ' . $createdReason
//        );
//
//        $copySite->setSiteId(null);
//        $copySite->setDomain($domain);
//
//        // NOTE: site::newInstance() does page copy too
//        $pages = $copySite->getPages();
//        $pageRevisions = [];
//
//        /** @var Page $page */
//        foreach ($pages as $page) {
//            $page->setAuthor($createdByUserId);
//            $page->setModifiedByUserId(
//                $createdByUserId,
//                'Copy site modified page in ' . get_class($this)
//                . ' for: ' . $createdReason
//            );
//            $pageRevision = $page->getPublishedRevision();
//            $pageRevisions[] = $pageRevision;
//            $entityManager->persist($page);
//            $entityManager->persist($pageRevision);
//        }
//
//        $containers = $copySite->getContainers();
//        $containerRevisions = [];
//
//        /** @var Container $container */
//        foreach ($containers as $container) {
//            $containerRevision = $container->getPublishedRevision();
//            $containerRevisions[] = $containerRevision;
//            $entityManager->persist($container);
//            $entityManager->persist($containerRevision);
//        }
//
//        $entityManager->persist($copySite);
//
//        if ($doFlush) {
//            $entityManager->flush($domain);
//            $entityManager->flush($copySite);
//            $entityManager->flush($pages->toArray());
//            $entityManager->flush($pageRevisions);
//            $entityManager->flush($containers->toArray());
//            $entityManager->flush($containerRevisions);
//        }
//
//        return $copySite;
//    }
}
