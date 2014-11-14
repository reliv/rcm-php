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
    public function getPageByName(SiteEntity $site,
        $pageName,
        $pageType='n'
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

        $return = array();

        foreach ($result as &$page) {
            $return[$page['pageId']] = $page['name'];
        }

        return $return;
    }

    /**
     * Add a new page to the DB
     *
     * @param string     $pageName  Page Name
     * @param string     $pageTitle Page Title
     * @param string     $layout    Site Layout
     * @param string     $author    Author
     * @param SiteEntity $site      Site Entity
     * @param string     $pageType  Page Type
     *
     * @return Page
     */
    public function createNewPage(
        $pageName,
        $pageTitle,
        $layout,
        $author,
        SiteEntity $site,
        $pageType = 'n'
    ) {
        $revision = new Revision();
        $revision->setAuthor($author);
        $revision->setCreatedDate(new \DateTime());

        $page = new PageEntity();
        $page->setCreatedDate(new \DateTime());
        $page->setAuthor($author);
        $page->setName($pageName);
        $page->setPageType($pageType);
        $page->setPageTitle($pageTitle);
        $page->setSite($site);
        $page->setStagedRevision($revision);
        $page->addRevision($revision);

        if ($layout != 'default') {
            $page->setSiteLayoutOverride($layout);
        }

        $this->_em->persist($revision);
        $this->_em->persist($page);

        $this->_em->flush(array($revision, $page));

        return $page;
    }

    /**
     * Copy a page
     *
     * @param PageEntity $pageToCopy      Page Entity to copy
     * @param string     $newPageName     Page Name or URL.
     * @param string     $author          Author of copied page
     * @param SiteEntity $siteDestination Site Entity to copy page to
     * @param string     $newPageTitle    Title of page
     * @param integer    $pageRevisionId  Page Revision ID to use for copy.  Defaults to currently published
     * @param string     $newPageType     Page type of page.  Defaults to "n"
     * @param boolean    $publishNewPage  Publish page instead of setting to staged
     *
     * @returns boolean
     *
     * @throws \Rcm\Exception\InvalidArgumentException
     * @throws \Rcm\Exception\PageNotFoundException
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function copyPage(
        PageEntity $pageToCopy,
        $newPageName,
        $author,
        SiteEntity $siteDestination,
        $newPageTitle = null,
        $pageRevisionId = null,
        $newPageType = 'n',
        $publishNewPage = false
    ) {
        if (empty($newPageName) || empty($author)) {
            throw new InvalidArgumentException(
                'Missing needed information to create page copy.'
            );
        }

        if (empty($newPageTitle)) {
            $newPageTitle = $pageToCopy->getPageTitle();
        }

        $clonedPage = clone $pageToCopy;
        $clonedPage->setName($newPageName);
        $clonedPage->setPageTitle($newPageTitle);
        $clonedPage->setAuthor($author);
        $clonedPage->setPageType($newPageType);
        $clonedPage->setSite($siteDestination);

        if (!empty($pageRevisionId) && is_numeric($pageRevisionId)) {
            $revisionToUse = $pageToCopy->getRevisionById($pageRevisionId);

            if (empty($revisionToUse)) {
                throw new PageNotFoundException(
                    'Page revision not found.'
                );
            }

            $clonedPage->setRevisions(array());
            $clonedRevision = clone $revisionToUse;
            $clonedPage->addRevision($clonedRevision);

            if ($publishNewPage) {
                $clonedPage->setPublishedRevision($clonedRevision);
            } else {
                $clonedPage->setStagedRevision($clonedRevision);
            }

        }

        $siteDestination->addPage($clonedPage);

        $this->_em->persist($clonedPage);
        $this->_em->flush($clonedPage);

        return true;
    }

    public function getOnlyPageIdByName($siteId, $name, $pageType='n')
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
            throw new PageNotFoundException('Unable to locate page by revision '.$revisionId);
        }

        $revision = $page->getRevisionById($revisionId);

        if (empty($revision)) {
            throw new RuntimeException('Revision not found.');
        }

        $page->setPublishedRevision($revision);

        $this->_em->flush(array($revision, $page));

        return $page;
    }

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

        $lastDraftQueryBuilder = $this->getRevisionQuery($siteId, $pageName, $pageType);

        $result = $publishedQueryBuilder->getQuery()->getSingleResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        try {
            $lastDraft = $lastDraftQueryBuilder->setMaxResults(1)->getSingleResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
            $lastDraft = array_values($lastDraft['revisions']);
        } catch (NoResultException $e) {
            $lastDraft = array(0=> null);
        }

        $result['lastDraft'] = $lastDraft[0];

        if ($result['lastDraft']['revisionId'] == $result['stagedRevision']['revisionId']) {
            $result['lastDraft'] = null;
        }

        return $result;
    }

    public function isValid($siteId, $pageName, $pageType='n')
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

    public function getRevisionList(
        $siteId,
        $pageName,
        $pageType='n',
        $published=false,
        $limit=10
    ) {
        $draftRevisionQuery = $this->getRevisionQuery($siteId, $pageName, $pageType, $published);

        if ($limit > 0) {
            $draftRevisionQuery->setMaxResults($limit);
        }

        try {
            $result = $draftRevisionQuery->getSingleResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        } catch (NoResultException $e) {
            return array();
        }

        return $result;
    }

    protected function getRevisionQuery($siteId, $pageName, $pageType, $published=false)
    {
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

    public function savePage(
        SiteEntity $siteEntity,
        $pageName,
        $pageRevision,
        $pageType='n',
        $saveData,
        $author
    ) {
        if (!empty($saveData['containers'])) {
            foreach($saveData['containers'] as $containerName => $containerData) {
                /** @var \Rcm\Entity\Container $container */
                $container = $siteEntity->getContainer($containerName);

                $this->saveContainer($container, $containerData, $author);
            }
        }

        $page = $siteEntity->getPage($pageName, $pageType);
        return $this->saveContainer($page, $saveData['pageContainer'], $author, $pageRevision);
    }
}
