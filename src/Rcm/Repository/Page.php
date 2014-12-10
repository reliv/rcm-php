<?php

/**
 * Page Repository
 *
 * This file contains the page repository
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

namespace Rcm\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Rcm\Entity\Container;
use Rcm\Entity\ContainerInterface;
use Rcm\Entity\Page as PageEntity;
use Rcm\Entity\PluginWrapper;
use Rcm\Entity\Revision;
use Rcm\Entity\Site as SiteEntity;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Exception\PageNotFoundException;
use Rcm\Exception\RuntimeException;

/**
 * Page Repository
 *
 * Page Repository.  Used to get custom page results from the DB
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class Page extends ContainerAbstract
{
    /**
     * Get a page entity by name
     *
     * @param SiteEntity $site     Site to lookup
     * @param string     $pageName Page Name
     * @param string     $pageType Page Type
     *
     * @return null|PageEntity
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPageByName(
        SiteEntity $site,
        $pageName,
        $pageType = 'n'
    ) {
        $queryBuilder = $this->createQueryBuilder('page')
            ->leftJoin('page.publishedRevision', 'publishedRevision')
            ->leftJoin('publishedRevision.pluginWrappers', 'pluginWrappers')
            ->leftJoin('pluginWrappers.instance', 'pluginInstances')
            ->where('page.site = :site')
            ->andWhere('page.name = :pageName')
            ->andWhere('page.pageType = :pageType')
            ->setParameter('site', $site)
            ->setParameter('pageName', $pageName)
            ->setParameter('pageType', $pageType);

        /** @var \Rcm\Entity\Page $result */
        return $queryBuilder->getQuery()->useQueryCache(true)->getOneOrNullResult();
    }


    /**
     * Gets the DB result of the Published Revision
     *
     * @param integer $siteId Site Id
     * @param string  $name   Name of the container
     * @param string  $type   Type of the container.  Currently only used by the page
     *                        container.
     *
     * @return mixed
     */
    public function getPublishedRevisionId($siteId, $name, $type = 'n')
    {
        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('publishedRevision.revisionId')
            ->from('\Rcm\Entity\Page', 'page')
            ->join('page.publishedRevision', 'publishedRevision')
            ->join('page.site', 'site')
            ->where('site.siteId = :siteId')
            ->andWhere('page.name = :pageName')
            ->andWhere('page.pageType = :pageType')
            ->setParameter('siteId', $siteId)
            ->setParameter('pageName', $name)
            ->setParameter('pageType', $type);

        try {
            return $queryBuilder->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * Get the Page Staged Revision Id and cache for later use
     *
     * @param integer     $siteId Site Id
     * @param string      $name   Page Name
     * @param null|string $type   Page Type.  Type "n" is default
     *
     * @return null|integer
     */
    public function getStagedRevisionId($siteId, $name, $type = 'n')
    {
        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('stagedRevision.revisionId')
            ->from('\Rcm\Entity\Page', 'page')
            ->join('page.stagedRevision', 'stagedRevision')
            ->join('page.site', 'site')
            ->where('site.siteId = :siteId')
            ->andWhere('page.name = :pageName')
            ->andWhere('page.pageType = :pageType')
            ->setParameter('siteId', $siteId)
            ->setParameter('pageName', $name)
            ->setParameter('pageType', $type);

        try {
            return $queryBuilder->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * Get Revision DB Info
     *
     * @param integer $siteId     Site Id
     * @param string  $name       Page Name
     * @param string  $revisionId Revision Id
     * @param string  $type       Container Type.
     *
     * @return null|array Database Result Set
     */
    public function getRevisionDbInfo($siteId, $name, $revisionId, $type = 'n')
    {
        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select(
            'page,
            site.siteId,
            publishedRevision.revisionId publishedRevisionId,
            stagedRevision.revisionId stagedRevisionId,
            revision,
            pluginWrappers,
            pluginInstances'
        )->from('\Rcm\Entity\Page', 'page')
            ->leftJoin('page.site', 'site')
            ->leftJoin('page.revisions', 'revision')
            ->leftJoin('page.publishedRevision', 'publishedRevision')
            ->leftJoin('page.stagedRevision', 'stagedRevision')
            ->leftJoin('revision.pluginWrappers', 'pluginWrappers')
            ->leftJoin('pluginWrappers.instance', 'pluginInstances')
            ->where('site.siteId = :siteId')
            ->andWhere('page.pageType = :pageType')
            ->andWhere('page.name = :pageName')
            ->andWhere('revision.revisionId = :revisionId')
            ->orderBy('pluginWrappers.layoutContainer')
            ->orderBy('pluginWrappers.renderOrder')
            ->setParameter('siteId', $siteId)
            ->setParameter('pageType', $type)
            ->setParameter('pageName', $name)
            ->setParameter('revisionId', $revisionId);

        $getData = $queryBuilder
            ->getQuery()
            ->getSingleResult(Query::HYDRATE_ARRAY);

        $result = null;

        if (!empty($getData)) {
            $result = $getData[0];
            $result['revision'] = $result['revisions'][$revisionId];
            $result['siteId'] = $getData['siteId'];
            $result['publishedRevisionId'] = $getData['publishedRevisionId'];
            $result['stagedRevisionId'] = $getData['stagedRevisionId'];
            unset($result['revisions'], $getData);
        }

        return $result;
    }

    /**
     * Get a list of page id's and page names by a given type
     *
     * @param integer $siteId SiteId
     * @param string  $type   Page Type to Search By
     *
     * @return array
     */
    public function getAllPageIdsAndNamesBySiteThenType($siteId, $type)
    {
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder->select('page.name, page.pageId')
            ->from('\Rcm\Entity\Page', 'page')
            ->join('page.site', 'site')
            ->where('page.pageType = :pageType')
            ->andWhere('site.siteId = :siteId')
            ->setParameter('pageType', $type)
            ->setParameter('siteId', $siteId);

        $result = $queryBuilder->getQuery()->getArrayResult();

        if (empty($result)) {
            return null;
        }

        $return = [];

        foreach ($result as &$page) {
            $return[$page['pageId']] = $page['name'];
        }

        return $return;
    }

    /**
     * createPage
     *
     * @param SiteEntity $site
     * @param array      $pageData
     * @param bool       $publishPage
     * @param bool       $doFlush
     *
     * @return PageEntity
     * @throws \Exception
     */
    public function createPage(
        SiteEntity $site,
        $pageData,
        $publishPage = false,
        $doFlush = true
    ) {
        if (empty($pageData['author'])) {
            throw new \Exception('Author is required to create a page.');
        }
        $revision = new Revision();
        $revision->setAuthor($pageData['author']);
        $revision->setCreatedDate(new \DateTime());

        // we should not have an Id on page create
        unset($pageData['pageId']);

        $page = new PageEntity();
        $page->populate($pageData);
        $page->setCreatedDate(new \DateTime());

        $page->setSite($site);

        if (!$publishPage) {
            $page->setStagedRevision($revision);
        } else {
            $page->setPublishedRevision($revision);
        }

        $page->addRevision($revision);

        $this->_em->persist($revision);
        $this->_em->persist($page);

        if ($doFlush) {
            $this->_em->flush(
                [
                    $revision,
                    $page
                ]
            );
        }

        return $page;
    }

    /**
     * createPages
     *
     * @param SiteEntity $site
     * @param array      $pagesData
     * @param bool       $publishPage
     * @param bool       $doFlush
     *
     * @return array
     * @throws \Exception
     */
    public function createPages(
        SiteEntity $site,
        $pagesData,
        $publishPage = false,
        $doFlush = false
    ) {
        $results = [];

        foreach ($pagesData as $name => $pageData) {

            $results[] = $this->createPage(
                $site,
                $pageData,
                $publishPage,
                $doFlush
            );
        }

        return $results;
    }

    /**
     * updatePage
     *
     * @param PageEntity $page
     * @param array      $pageData
     * @param bool       $doFlush
     *
     * @return void
     */
    public function updatePage(
        PageEntity $page,
        $pageData,
        $doFlush = true
    ) {

        // Values cannot be changed
        unset($pageData['pageId']);
        unset($pageData['author']);
        unset($pageData['createdDate']);
        unset($pageData['lastPublished']);

        $page->populate($pageData);

        $this->getEntityManager()->persist($page);
        if ($doFlush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Copy a page
     *
     * @param SiteEntity $destinationSite Site Entity to copy page to
     * @param PageEntity $pageToCopy      Page Entity to copy
     * @param array      $pageData        Array of data to populate the page entity
     * @param null       $pageRevisionId  Page Revision ID to use for copy.  Defaults to currently published
     * @param bool       $publishNewPage  Publish page instead of setting to staged
     * @param bool       $doFlush         Force flush
     *
     * @return PageEntity
     */
    public function copyPage(
        SiteEntity $destinationSite,
        PageEntity $pageToCopy,
        $pageData,
        $pageRevisionId = null,
        $publishNewPage = false,
        $doFlush = true
    ) {

        if (empty($pageData['name'])) {
            throw new InvalidArgumentException(
                'Missing needed information (name) to create page copy.'
            );
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

        $pageData['site'] = $destinationSite;

        $clonedPage = clone $pageToCopy;
        $clonedPage->populate($pageData);
        $revisionToUse = $clonedPage->getStagedRevision();

        if (!empty($pageRevisionId)) {
            $sourceRevision = $pageToCopy->getRevisionById($pageRevisionId);

            if (empty($sourceRevision)) {
                throw new PageNotFoundException(
                    'Page revision not found.'
                );
            }

            $revisionToUse = clone $sourceRevision;
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

        $this->_em->persist($clonedPage);

        if ($doFlush) {
            $this->_em->flush($clonedPage);
        }

        return $clonedPage;
    }

    /**
     * getOnlyPageIdByName
     *
     * @param        $siteId
     * @param        $name
     * @param string $pageType
     *
     * @return mixed
     */
    public function getOnlyPageIdByName($siteId, $name, $pageType = 'n')
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('page.pageId')
            ->from('\Rcm\Entity\Page', 'page')
            ->where('page.name = :pageName')
            ->andWhere('page.pageType = :pageType')
            ->andWhere('page.site = :siteId')
            ->setParameter('pageName', $name)
            ->setParameter('pageType', $pageType)
            ->setParameter('siteId', $siteId);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * Get a page entity containing a Revision Id.
     *
     * @param integer $siteId     Site Id
     * @param string  $pageName   Name of page
     * @param string  $pageType   Page Type
     * @param integer $revisionId Revision Id to search for
     *
     * @return Page
     * @throws PageNotFoundException
     * @throws RuntimeException
     */
    public function publishPageRevision($siteId, $pageName, $pageType, $revisionId)
    {
        //Query is needed to ensure revision belongs to the page in question
        $pageQueryBuilder = $this->_em->createQueryBuilder();
        $pageQueryBuilder->select('page, revision')
            ->from('\Rcm\Entity\Page', 'page')
            ->join('page.revisions', 'revision')
            ->where('page.name = :pageName')
            ->andWhere('page.pageType = :pageType')
            ->andWhere('page.site = :siteId')
            ->andWhere('revision.revisionId = :revisionId')
            ->setParameter('pageName', $pageName)
            ->setParameter('pageType', $pageType)
            ->setParameter('siteId', $siteId)
            ->setParameter('revisionId', $revisionId);

        /** @var \Rcm\Entity\Page $page */
        $page = $pageQueryBuilder->getQuery()->getSingleResult();

        if (empty($page)) {
            throw new PageNotFoundException(
                'Unable to locate page by revision ' . $revisionId
            );
        }

        $revision = $page->getRevisionById($revisionId);

        if (empty($revision)) {
            throw new RuntimeException('Revision not found.');
        }

        $page->setPublishedRevision($revision);

        $this->_em->flush(
            [
                $revision,
                $page
            ]
        );

        return $page;
    }

    /**
     * getPageRevisionList
     *
     * @param $siteId
     * @param $pageName
     * @param $pageType
     *
     * @return mixed
     * @throws NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPageRevisionList($siteId, $pageName, $pageType)
    {
        $publishedQueryBuilder = $this->_em->createQueryBuilder();

        $publishedQueryBuilder->select('PARTIAL page.{pageId}, published, staged ')
            ->from('\Rcm\Entity\Page', 'page')
            ->leftjoin('page.publishedRevision', 'published')
            ->leftjoin('page.stagedRevision', 'staged')
            ->where('page.site = :siteId')
            ->andWhere('page.name = :pageName')
            ->andWhere('page.pageType = :pageType')
            ->setParameter('siteId', $siteId)
            ->setParameter('pageName', $pageName)
            ->setParameter('pageType', $pageType);

        $lastDraftQueryBuilder = $this->getRevisionQuery(
            $siteId,
            $pageName,
            $pageType
        );

        $result = $publishedQueryBuilder->getQuery()->getSingleResult(
            \Doctrine\ORM\Query::HYDRATE_ARRAY
        );

        try {
            $lastDraft = $lastDraftQueryBuilder->setMaxResults(1)->getSingleResult(
                \Doctrine\ORM\Query::HYDRATE_ARRAY
            );
            $lastDraft = array_values($lastDraft['revisions']);
        } catch (NoResultException $e) {
            $lastDraft = [0 => null];
        }

        $result['lastDraft'] = $lastDraft[0];

        if ($result['lastDraft']['revisionId']
            == $result['stagedRevision']['revisionId']
        ) {
            $result['lastDraft'] = null;
        }

        return $result;
    }

    /**
     * isValid
     *
     * @param        $siteId
     * @param        $pageName
     * @param string $pageType
     *
     * @return bool
     */
    public function isValid($siteId, $pageName, $pageType = 'n')
    {
        $isValidQueryBuilder = $this->_em->createQueryBuilder();
        $isValidQueryBuilder->select('page.pageId')
            ->from('\Rcm\Entity\Page', 'page')
            ->where('page.name = :pageName')
            ->andWhere('page.pageType = :pageType')
            ->andWhere('page.site = :siteId')
            ->setParameter('pageName', $pageName)
            ->setParameter('pageType', $pageType)
            ->setParameter('siteId', $siteId);


        $result = $isValidQueryBuilder->getQuery()->getScalarResult();

        if (!empty($result)) {
            return true;
        }

        return false;
    }

    /**
     * getRevisionList
     *
     * @param        $siteId
     * @param        $pageName
     * @param string $pageType
     * @param bool   $published
     * @param int    $limit
     *
     * @return array|mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getRevisionList(
        $siteId,
        $pageName,
        $pageType = 'n',
        $published = false,
        $limit = 10
    ) {
        $draftRevisionQuery = $this->getRevisionQuery(
            $siteId,
            $pageName,
            $pageType,
            $published
        );

        if ($limit > 0) {
            $draftRevisionQuery->setMaxResults($limit);
        }

        try {
            $result = $draftRevisionQuery->getSingleResult(
                \Doctrine\ORM\Query::HYDRATE_ARRAY
            );
        } catch (NoResultException $e) {
            return [];
        }

        return $result;
    }

    /**
     * getRevisionQuery
     *
     * @param      $siteId
     * @param      $pageName
     * @param      $pageType
     * @param bool $published
     *
     * @return Query
     */
    protected function getRevisionQuery(
        $siteId,
        $pageName,
        $pageType,
        $published = false
    ) {
        $revisionQueryBuilder = $this->_em->createQueryBuilder();
        $revisionQueryBuilder->select('page.pageId, revisions')
            ->select('PARTIAL page.{pageId}, revisions ')
            ->from('\Rcm\Entity\Page', 'page')
            ->join('page.revisions', 'revisions')
            ->where('page.site = :siteId')
            ->andWhere('page.name = :pageName')
            ->andWhere('page.pageType = :pageType')
            ->andWhere('revisions.published = :published')
            ->setParameter('siteId', $siteId)
            ->setParameter('pageName', $pageName)
            ->setParameter('pageType', $pageType)
            ->setParameter('published', $published)
            ->orderBy('revisions.revisionId', 'DESC');

        if ($published) {
            $revisionQueryBuilder->andWhere('revisions != page.publishedRevision');
        }

        return $revisionQueryBuilder->getQuery();
    }

    /**
     * savePage
     *
     * @param SiteEntity $siteEntity
     * @param            $pageName
     * @param            $pageRevision
     * @param string     $pageType
     * @param            $saveData
     * @param            $author
     *
     * @return int|null
     */
    public function savePage(
        SiteEntity $siteEntity,
        $pageName,
        $pageRevision,
        $pageType = 'n',
        $saveData,
        $author
    ) {
        if (!empty($saveData['containers'])) {
            foreach ($saveData['containers'] as $containerName => $containerData) {
                /** @var \Rcm\Entity\Container $container */
                $container = $siteEntity->getContainer($containerName);

                if (empty($container)) {
                    /** @var \Rcm\Repository\Container $containerRepo */
                    $containerRepo = $this->_em->getRepository(
                        '\Rcm\Entity\Container'
                    );
                    $container = $containerRepo->createContainer(
                        $siteEntity,
                        $containerName,
                        $author
                    );
                }

                $this->saveContainer($container, $containerData, $author);
            }
        }

        $page = $siteEntity->getPage($pageName, $pageType);
        return $this->saveContainer(
            $page,
            $saveData['pageContainer'],
            $author,
            $pageRevision
        );
    }
}
