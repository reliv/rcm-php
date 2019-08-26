<?php

namespace Rcm\SecurityPropertyProvider;

use Doctrine\ORM\EntityManager;
use Rcm\Acl\SecurityPropertiesProviderInterface;
use Rcm\Acl2\SecurityPropertyConstants;

class PageSecurityPropertyProvider implements SecurityPropertiesProviderInterface
{
    protected $entityManager;

    public function __construct(EntityManager $entityManger)
    {
        $this->entityManager = $entityManger;
    }

    public function findSecurityProperties($data): array
    {
        if (!array_key_exists('siteId', $data)) {
            throw new NotAllowedBySecurityPropGenerationFailure('siteId not passed.');
        }

        /**
         * @var \Rcm\Entity\Site|null $site
         */
        $site = $this->entityManager->getRepository(\Rcm\Entity\Site::class)->find($data['siteId']);

        if ($site === null) {
            throw new NotAllowedBySecurityPropGenerationFailure('Site not found.');
        }

        return [
            'type' => SecurityPropertyConstants::TYPE_CONTENT,
            SecurityPropertyConstants::CONTENT_TYPE_KEY => SecurityPropertyConstants::CONTENT_TYPE_PAGE,
            'country' => $site->getCountryIso3()
        ];
    }
}
