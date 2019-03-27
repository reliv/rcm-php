<?php

namespace Rcm\SiteSettingsSections;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Site;

class GetSection
{
    /** @var GetSectionDefinitions */
    protected $getSectionDefinitions;

    /** @var EntityManager */
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
        $definitions = $this->getSectionDefinitions->__invoke();
        if (!array_key_exists($sectionName, $definitions)) {
            throw new InvalidSectionNameException();
        }

        $repository = $this->entityManager->getRepository(SiteSettingsSectionEntity::class);

        /**
         * @var $entity SiteSettingsSectionEntity
         */
        $entity = $repository->findOneBy(['site' => $site, 'sectionName' => $sectionName]);
        if ($entity === null) {
            return $this->getDefaults($definitions[$sectionName]);
        }

        return $entity->getSettings();
    }

    protected function getDefaults(array $definition)
    {
        $defaults = [];
        foreach ($definition['fields'] as $field) {
            if (empty($field['name'])) {
                continue;
            }
            $defaults[$field['name']] = $field['default'] ?? null;
        }
        return $defaults;
    }
}
