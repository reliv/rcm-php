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
 * @link      http://ci.reliv.com/confluence
 */
namespace Rcm\Model;

use Rcm\Model\FactoryAbstract,
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
 * @link      http://ci.reliv.com/confluence
 */

class PageFactory extends FactoryAbstract
{


    /**
     * Creates a page and saves it to the db
     *
     * @param string $name            name
     * @param string $author          author
     * @param string $pageTitle       page title
     * @param string $pageDescription page description
     * @param string $metaKeywords    meta keywords
     * @param string $pageLayout      page layout
     * @param Site   $baseSite        base site
     * @param array  $plugins         plugin array
     *
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
        $template=false
    ) {
        $entityManager = $this->getEm();

        $page = new \Rcm\Entity\Page();
        $page->setName($name);
        $page->setAuthor($author);
        $page->setCreatedDate(new \DateTime("now"));
        $page->setLastPublished(new \DateTime("now"));
        $page->setSite($baseSite);

        if ($template === true) {
            $page->setIsTemplate();
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
        $page->setPublishedRevision($pageRevision);
        
        $baseSite->addPage($page);

        $entityManager->persist($baseSite);
        $entityManager->persist($pageRevision);
        $entityManager->persist($page);
        
        $entityManager->flush();

        return $page;
    }


}