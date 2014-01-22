<?php

/**
 * Page Factory
 *
 * Creates a new page object in the database
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm\Model\
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
namespace Rcm\Model;

use Rcm\Model\EntityMgrAware,
    Zend\View\Helper\ViewModel,
    Doctrine\ORM\EntityManager,
    Rcm\Entity\Site;

/**
 * Page Factory
 *
 * Creates a new page object in the database
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm\Model\
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: Release: 1.0
 */

class PageFactory extends EntityMgrAware
{
    /**
     * @param $name
     * @param $author
     * @param $pageTitle
     * @param $pageDescription
     * @param $metaKeywords
     * @param $pageLayout
     * @param \Rcm\Entity\Site $baseSite
     * @param string $plugins
     * @param string $pageType
     * @param bool $publish
     * @param bool $skipDbFlush Used to speed up large batch jobs
     * @return \Rcm\Entity\Page
     */
    public function createPage(
        $name,
        $author,
        $pageTitle,
        $pageDescription,
        $metaKeywords,
        $pageLayout,
        \Rcm\Entity\Site $baseSite,
        $plugins='',
        $pageType = 'n',
        $publish = false,
        $skipDbFlush = false
    ) {
        $entityMgr = $this->entityMgr;

        $page = new \Rcm\Entity\Page();
        $page->setName($name);
        $page->setAuthor($author);
        $page->setCreatedDate(new \DateTime("now"));

        if ($publish === true) {
            $page->setLastPublished(new \DateTime("now"));
        }

        $page->setSite($baseSite);

        if ($pageType !== 'n') {
            $page->setPageType($pageType);
        }
        
        $pageRevision = new \Rcm\Entity\PageRevision();
        $pageRevision->setAuthor($author);
        $pageRevision->setCreatedDate(new \DateTime("now"));
        $pageRevision->setPage($page);
        $pageRevision->setPageTitle($pageTitle);
        $pageRevision->setDescription($pageDescription);
        $pageRevision->setKeywords($metaKeywords);
        $pageRevision->setPageLayout($pageLayout);

        if (!empty($plugins)) {
            $pageRevision->AddInstances($plugins);
        }

        $page->addPageRevision($pageRevision);

        if ($publish === true || $pageType === 't') {
            $page->setPublishedRevision($pageRevision);
        } else {
            $page->setStagedRevision($pageRevision);
        }
        
        $baseSite->addPage($page);

        $entityMgr->persist($baseSite);
        $entityMgr->persist($pageRevision);
        $entityMgr->persist($page);

        if(!$skipDbFlush){
            $entityMgr->flush();
        }

        return $page;
    }


}