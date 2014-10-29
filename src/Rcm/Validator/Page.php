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

use Rcm\Repository\Page as PageRepo;

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

    /** @var \Rcm\Repository\Page */
    protected $pageRepo;

    protected $pageType = 'n';

    protected $siteId = null;

    /**
     * Constructor
     *
     * @param PageRepo $pageRepo Rcm Page Manager
     */
    public function __construct(PageRepo $pageRepo)
    {
        $this->messageTemplates[self::PAGE_EXISTS]
            = "Page '%value%' already exists";

        $this->pageRepo = $pageRepo;

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
     * Set the site id to use for validation.  If none is passed then we will
     * validate against the current site id.
     *
     * @param integer $siteId Site Id
     *
     * @return void
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
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

        $check = $this->pageRepo->findOneBy(
            array(
                'name' => $value,
                'pageType' => $this->pageType,
                'site' => $this->siteId
            )
        );

        if (!empty($check)) {
            $this->error(self::PAGE_EXISTS);

            return false;
        }

        return true;
    }
}
