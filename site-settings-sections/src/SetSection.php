<?php

namespace Rcm\SiteSettingsSections;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Site;
use Rcm\ImmutableHistory\SiteSettingsSection\SiteSettingsSectionContent;
use Rcm\ImmutableHistory\SiteSettingsSection\SiteSettingsSectionLocator;
use Rcm\ImmutableHistory\VersionRepositoryInterface;

class SetSection
{
    protected $getSectionDefinitions;

    protected $entityManager;
    protected $versionRepository;

    public function __construct(
        GetSectionDefinitions $getSectionDefinitions,
        EntityManager $entityManager,
        VersionRepositoryInterface $versionRepository
    ) {
        $this->getSectionDefinitions = $getSectionDefinitions;
        $this->entityManager = $entityManager;
        $this->versionRepository = $versionRepository;
    }

    /**
     * /**
     * @param Site $site
     * @param string $sectionName
     * @param array $settings
     * @param string $userId
     * @throws InvalidSectionNameException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function __invoke(
        Site $site,
        string $sectionName,
        array $settings,
        string $userId
    ) {
        if (!array_key_exists($sectionName, $this->getSectionDefinitions->__invoke())) {
            throw new InvalidSectionNameException();
        }

        $repository = $this->entityManager->getRepository(SiteSettingsSectionEntity::class);
        $oldEntity = $repository->findOneBy(['site' => $site, 'sectionName' => $sectionName]);
        if ($oldEntity !== null) {
            $this->entityManager->remove($oldEntity);
            $this->entityManager->flush($oldEntity);
        }
        $newEntity = new SiteSettingsSectionEntity($site, $sectionName, $settings, new \DateTime());
        $this->entityManager->persist($newEntity);
        $this->entityManager->flush($newEntity);

        $this->versionRepository->publish(
            new SiteSettingsSectionLocator($site->getSiteId(), $sectionName),
            new SiteSettingsSectionContent($settings),
            $userId,
            __CLASS__ . '::' . __FUNCTION__
        );
    }
}
