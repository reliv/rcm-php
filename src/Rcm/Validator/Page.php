<?php
/**
 * Rcm Page Validator
 *
 * This file contains the class definition for the Page Validator
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

namespace Rcm\Validator;

use Rcm\Service\PageManager;

/**
 * Rcm Page Validator
 *
 * Rcm Page Validator. This validator will verify that the page
 * name meets requirements and that a page of the same name doesn't exist.
 * Page names can only be alpha numeric and only contain the additional
 * characters of "-_"
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
class Page extends PageName
{
    const PAGE_EXISTS = 'pageExists';

    protected $pageNameOk = false;

    /** @var \Rcm\Service\PageManager  */
    protected $pageManager;

    protected $pageType = 'n';

    /**
     * Constructor
     *
     * @param PageManager $pageManager Rcm Page Manager
     */
    public function __construct(PageManager $pageManager)
    {
        $this->messageTemplates[self::PAGE_EXISTS] = "Page '%value%' already exists";
        $this->pageManager = $pageManager;

        parent::__construct();
    }

    /**
     * Set the page type to use for validation
     *
     * @param string $pageType Page type
     *
     * @return void
     */
    public function setPageType($pageType)
    {
        $this->pageType = $pageType;
    }

    /**
     * Is the page valid?
     *
     * @param string $value Page to validate
     *
     * @return bool
     */
    public function isValid($value)
    {
        $nameOk = parent::isValid($value);

        if (!$nameOk) {
            return false;
        }

        if ($this->pageManager->getPageByName($value, $this->pageType)) {
            $this->error(self::PAGE_EXISTS);
            return false;
        }

        return true;
    }
}