<?php
/**
 * Rcm Page Manager
 *
 * This file contains the class definition for the Page Manager
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
 * @link      http://github.com/reliv
 */

namespace Rcm\Service;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Validator\Page;
use Rcm\Validator\PageTemplate;
use Zend\Cache\Storage\StorageInterface;
use Zend\Validator\AbstractValidator;

/**
 * Rcm Page Manager
 *
 * Rcm Page Manager.  This class handles everything about a CMS page.  Pages also
 * include their own plugin containers and can contain multiple containers depending
 * on the page template defined by the CMS theme being used.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 */
class PageManager extends ContainerAbstract
{
    /** @var \Rcm\Repository\Page  */
    protected $repository;

    protected $layoutValidator;

    /**
     * Constructor
     *
     * @param PluginManager     $pluginManager   Rcm Plugin Manager
     * @param EntityRepository  $repository      Doctrine Entity Manager
     * @param StorageInterface  $cache           Zend Cache Manager
     * @param SiteManager       $siteManager     Rcm Site Manager
     * @param AbstractValidator $layoutValidator Rcm Main Layout Validator
     */
    public function __construct(
        PluginManager $pluginManager,
        EntityRepository $repository,
        StorageInterface $cache,
        SiteManager $siteManager,
        AbstractValidator $layoutValidator
    ) {
        parent::__construct(
            $pluginManager,
            $repository,
            $cache,
            $siteManager
        );

        $this->layoutValidator = $layoutValidator;
    }

    /**
     * Get a page list by type.  Returns page Id and page name.
     *
     * @param string  $type   Page Type
     * @param integer $siteId Site Id
     *
     * @return array
     * @throws \RuntimeException
     **/
    public function getPageListByType($type, $siteId=null)
    {
        if (!$siteId) {
            $siteId = $this->siteManager->getCurrentSiteId();
        }

        if (!$this->siteManager->isValidSiteId($siteId)) {
            throw new \RuntimeException('Invalid Site ID');
        }

        return $this->repository->getAllPageIdsAndNamesBySiteThenType(
            $siteId,
            $type
        );
    }

    /**
     * Get a page by name
     *
     * @param string  $name     Page Name to Search
     * @param string  $pageType Page Type
     * @param integer $siteId   Site Id.  Will use current set siteId in not passed
     *                          in.
     *
     * @return null|object
     * @throws \RuntimeException
     */
    public function getPageByName($name, $pageType='n', $siteId=null)
    {
        if (!$siteId) {
            $siteId = $this->siteManager->getCurrentSiteId();
        }

        if (!$this->siteManager->isValidSiteId($siteId)) {
            throw new \RuntimeException('Invalid Site ID');
        }

        return $this->repository->findOneBy(
            array(
                'name' => $name,
                'pageType' => $pageType,
                'site' => $siteId
            )
        );
    }

    /**
     * Get a page by Id
     *
     * @param string  $pageId   Page Name to Search
     * @param string  $pageType Page Type
     * @param integer $siteId   Site Id.  Will use current set siteId in not passed
     *                          in.
     *
     * @return null|object
     * @throws \RuntimeException
     */
    public function getPageById($pageId, $pageType='t', $siteId=null)
    {
        if (!$siteId) {
            $siteId = $this->siteManager->getCurrentSiteId();
        }

        if (!$this->siteManager->isValidSiteId($siteId)) {
            throw new \RuntimeException('Invalid Site ID');
        }

        return $this->repository->findOneBy(
            array(
                'pageId' => $pageId,
                'pageType' => $pageType,
                'site' => $siteId
            )
        );
    }

    /**
     * Create a new page
     *
     * @param string $pageName  Page Name
     * @param string $pageTitle Page Title
     * @param string $layout    Site Layout
     * @param string $author    Author
     * @param string $pageType  Page Type
     * @param null   $siteId    Site Id
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function createNewPage(
        $pageName,
        $pageTitle,
        $layout,
        $author,
        $pageType='n',
        $siteId=null
    ) {
        if (!$siteId) {
            $siteId = $this->siteManager->getCurrentSiteId();
        }

        $this->validateNewPageData($pageName, $pageTitle, $layout, $pageType);

        $site = $this->siteManager->getSiteById($siteId);

        if (!$site) {
            throw new \RuntimeException('Invalid Site ID');
        }

        $this->repository->createNewPage(
            $pageName,
            $pageTitle,
            $layout,
            $author,
            $site
        );
    }

    /**
     * Validate data needed for a new page
     *
     * @param string $pageName  Page name to validate
     * @param string $pageTitle Page Title to validate
     * @param string $layout    Page Layout to Validate
     * @param string $pageType  Page Type
     *
     * @return void
     * @throws \Rcm\Exception\InvalidArgumentException
     */
    protected function validateNewPageData(
        $pageName,
        $pageTitle,
        $layout,
        $pageType='n'
    ) {
        if (empty($pageName)) {
            throw new InvalidArgumentException(
                'Missing Page name'
            );
        }

        if (empty($pageTitle)) {
            throw new InvalidArgumentException(
                'Missing Page Title'
            );
        }

        if (empty($layout)) {
            throw new InvalidArgumentException(
                'Missing Page Title'
            );
        }

        $pageValidator = $this->getPageValidator($pageType);

        if (!$pageValidator->isValid($pageName)) {
            $messages = $pageValidator->getMessages();

            $error = implode("\n", $messages);

            throw new InvalidArgumentException(
                $error
            );
        }

        if (!$this->layoutValidator->isValid($layout)) {
            $messages = $this->layoutValidator->getMessages();

            $error = implode("\n", $messages);

            throw new InvalidArgumentException(
                $error
            );
        }
    }

    /**
     * Returns the Zend Page Validator
     *
     * @param string  $pageType Page type for validator
     * @param integer $siteId   Site Id.  Will use current set siteId in not passed
     *                          in.
     *
     * @return Page
     * @throws \RuntimeException
     */
    public function getPageValidator($pageType='n', $siteId=null)
    {
        if (!$siteId) {
            $siteId = $this->siteManager->getCurrentSiteId();
        }

        if (!$this->siteManager->isValidSiteId($siteId)) {
            throw new \RuntimeException('Invalid Site ID');
        }

        $validator = new Page($this);
        $validator->setPageType($pageType);

        return $validator;
    }

    /**
     * Returns the Zend Template Validator
     *
     * @param integer $siteId Site Id.  Will use current set siteId in not passed in.
     *
     * @return PageTemplate
     * @throws \RuntimeException
     */
    public function getTemplateValidator($siteId=null)
    {
        if (!$siteId) {
            $siteId = $this->siteManager->getCurrentSiteId();
        }

        if (!$this->siteManager->isValidSiteId($siteId)) {
            throw new \RuntimeException('Invalid Site ID');
        }

        $validator = new PageTemplate($this);
        $validator->setSiteId($siteId);

        return $validator;
    }
}