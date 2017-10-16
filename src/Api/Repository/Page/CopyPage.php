<?php

namespace Rcm\Api\Repository\Page;

use Doctrine\ORM\EntityManager;
use Rcm\Api\Repository\Options;
use Rcm\Api\Repository\Site\FindSite;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Exception\PageNotFoundException;
use Rcm\Exception\RuntimeException;
use Rcm\Exception\SiteNotFoundException;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CopyPage
{
    const OPTION_PAGE_REVISION_ID = 'pageRevisionId';
    const OPTION_PUBLISH_NEW_PAGE = 'publishNewPage';
    const OPTION_DO_FLUSH = 'doFlush';

    protected $entityManager;
    protected $findSite;
    protected $findPageById;
    protected $assertCanCreateSitePage;

    /**
     * @param EntityManager           $entityManager
     * @param FindSite                $findSite
     * @param FindPageById            $findPageById
     * @param AssertCanCreateSitePage $assertCanCreateSitePage
     */
    public function __construct(
        EntityManager $entityManager,
        FindSite $findSite,
        FindPageById $findPageById,
        AssertCanCreateSitePage $assertCanCreateSitePage
    ) {
        $this->entityManager = $entityManager;
        $this->findSite = $findSite;
        $this->findPageById = $findPageById;
        $this->assertCanCreateSitePage = $assertCanCreateSitePage;
    }

    /**
     * @param int|string $destinationSiteId
     * @param int|string $pageToCopyId
     * @param array      $pageData
     * @param array      $options
     *
     * @return Page
     */
    public function __invoke(
        $destinationSiteId,
        $pageToCopyId,
        array $pageData,
        array $options = []
    ):Page {
        $pageRevisionId = Options::get(
            $options,
            static::OPTION_PAGE_REVISION_ID,
            null
        );
        $publishNewPage = Options::get(
            $options,
            static::OPTION_PUBLISH_NEW_PAGE,
            false
        );

        $doFlush = Options::get(
            $options,
            static::OPTION_DO_FLUSH,
            true
        );

        if (empty($pageData['name'])) {
            throw new InvalidArgumentException(
                'Missing needed information (name) to create page copy.'
            );
        }

        if (empty($pageData['createdByUserId'])) {
            throw new InvalidArgumentException(
                'Missing needed information (createdByUserId) to create page copy.'
            );
        }

        if (empty($pageData['createdReason'])) {
            $pageData['createdReason'] = 'Copy page in ' . get_class($this);
        }

        if (empty($pageData['author'])) {
            throw new InvalidArgumentException(
                'Missing needed information (author) to create page copy.'
            );
        }

        // Values cannot be changed
        unset($pageData['pageId']);
        unset($pageData['createdDate']);
        unset($pageData['lastPublished']);

        $destinationSite = $this->findSite->__invoke(
            $destinationSiteId
        );

        if (empty($destinationSite)) {
            throw new SiteNotFoundException(
                'Destination site not found with ID: ' . $destinationSiteId
            );
        }

        $pageData['site'] = $destinationSite;

        $pageToCopy = $this->findPageById->__invoke(
            $pageToCopyId
        );

        if (empty($pageToCopy)) {
            throw new PageNotFoundException(
                'Page to copy not found with ID: ' . $pageToCopyId
            );
        }

        $clonedPage = $pageToCopy->newInstance(
            $pageData['createdByUserId'],
            $pageData['createdReason']
        );
        $clonedPage->populate($pageData);

        /** @var Site $clonedPageSite */
        $clonedPageSite = $clonedPage->getSite();

        if (empty($clonedPageSite)) {
            throw new RuntimeException(
                'Cloned page site not found.'
            );
        }

        $this->assertCanCreateSitePage->__invoke(
            $clonedPageSite->getSiteId(),
            $clonedPage->getName(),
            $clonedPage->getPageType()
        );

        $revisionToUse = $clonedPage->getStagedRevision();

        if (!empty($pageRevisionId)) {
            $sourceRevision = $pageToCopy->getRevisionById($pageRevisionId);

            if (empty($sourceRevision)) {
                throw new PageNotFoundException(
                    'Page revision not found.'
                );
            }

            $revisionToUse = $sourceRevision->newInstance(
                $pageData['createdByUserId'],
                $pageData['createdReason']
            );
            $clonedPage->setRevisions([]);
            $clonedPage->addRevision($revisionToUse);
        }

        if (empty($revisionToUse)) {
            throw new RuntimeException(
                'Page revision not found.'
            );
        }

        if ($publishNewPage) {
            $clonedPage->setPublishedRevision($revisionToUse);
        } else {
            $clonedPage->setStagedRevision($revisionToUse);
        }

        $destinationSite->addPage($clonedPage);

        $this->entityManager->persist($clonedPage);

        if ($doFlush) {
            $this->entityManager->flush($clonedPage);
        }

        return $clonedPage;
    }
}
