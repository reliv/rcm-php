<?php
/**
 * PageSearchApiController
 *
 * Search through pages and return URL
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller
 * @author    author Brian Janish <bjanish@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: GIT:
 * @link      https://github.com/reliv
 */
namespace Rcm\Controller;

use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * PageSearchApiController
 *
 * Search through pages and return URL
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller
 * @author    author Brian Janish <bjanish@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: GIT:
 * @link      https://github.com/reliv
 */
class PageSearchApiController extends AbstractRestfulController
{
    /**
     * siteTitleSearchAction
     *
     * @return JsonModel
     */
    public function siteTitleSearchAction()
    {
        $query = $this->getEvent()->getRouteMatch()->getParam('query');
        $entityMgr = $this->getServiceLocator()->get(
            'Doctrine\ORM\EntityManager'
        );

        $currentSite = $this->getServiceLocator()->get(
            'Rcm\Service\CurrentSite'
        );
        $siteId = $currentSite->getSiteId();

        $results = $entityMgr->createQuery(
            '
                        select page.name, page.pageTitle, page.pageType from Rcm\\Entity\\Page page
                        join page.site site
                        where (page.name like :query or page.pageTitle like :query) and site.siteId like :siteId
                    '
        )->setParameter('query', '%' . $query . '%')
            ->setParameter('siteId', '%' . $siteId . '%')
            ->getResult();

        $pageNames = [];
        foreach ($results as $result) {

            $pageNames[$result['name']] = [
                'title' => $result['pageTitle'],
                'url' => $this->urlToPage($result['name'], $result['pageType'])
            ];
        }
        return new JsonModel($pageNames);
    }

    /**
     * allSitePagesAction
     *
     * @return JsonModel
     */
    public function allSitePagesAction()
    {
        $entityMgr = $this->getServiceLocator()->get(
            'Doctrine\ORM\EntityManager'
        );
        $currentSite = $this->getServiceLocator()->get(
            'Rcm\Service\CurrentSite'
        );
        $siteId = $currentSite->getSiteId();

        $site = $entityMgr->getRepository(
            '\Rcm\Entity\Site'
        )->findOneBy(
                [
                    'siteId' => $siteId
                ]
            );
        /**
         * @var \Rcm\Entity\Page $pages
         */
        $pages = $site->getPages();

        /**@var \Rcm\Entity\Page $page */
        foreach ($pages as $page) {
            $pageName = $page->getName();
            $pageUrl = $this->urlToPage($pageName, $page->getPageType());
            if (isset($_GET['format'])
                && $_GET['format'] == 'tinyMceLinkList'
            ) {
                $return[] = [
                    'title' => $pageUrl,
                    'value' => $pageUrl
                ];
            } else {
                $return[$pageUrl] = $pageName;
            }
        }
        asort($return);
        return new JsonModel($return);
    }
}
