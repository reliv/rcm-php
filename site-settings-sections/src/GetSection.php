<?php

namespace Rcm\SiteSettingsSections;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Site;

class GetSection
{
    protected $getSectionDefinitions;

    protected $entityManager;

    public function __construct(
        GetSectionDefinitions $getSectionDefinitions,
        EntityManager $entityManager
    ) {
        $this->getSectionDefinitions = $getSectionDefinitions;
        $this->entityManager = $entityManager;
    }

    /**
     * Returns the settings if found or null if not found.
     *
     * @param Site $site
     * @param string $sectionName
     * @return array|null
     * @throws InvalidSectionNameException
     */
    public function __invoke(Site $site, string $sectionName)
    {
        if (!array_key_exists($sectionName, $this->getSectionDefinitions->__invoke())) {
            throw new InvalidSectionNameException();
        }

        $repository = $this->entityManager->getRepository(SiteSettingsSectionEntity::class);

        /**
         * @var $entity SiteSettingsSectionEntity
         */
        $entity = $repository->findOneBy(['site' => $site, 'sectionName' => $sectionName]);
        if ($entity === null) {
            return null;
        }

        return $entity->getSettings();
    }
}
