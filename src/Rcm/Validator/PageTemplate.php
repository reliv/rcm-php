<?php
/**
 * Rcm Page Template Validator
 *
 * This file contains the class definition for the Page Template Validator
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

use Rcm\Entity\Site;
use Rcm\Repository\Page as PageRepo;
use Zend\Validator\AbstractValidator;

/**
 * Rcm Page Template Validator
 *
 * Rcm Page Template Validator. This validator will verify that the page template
 * exists.
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
class PageTemplate extends AbstractValidator
{
    const PAGE_TEMPLATE = 'pageTemplate';

    protected $messageTemplates
        = array(
            self::PAGE_TEMPLATE => "'%value%' is not a valid page template."
        );

    /** @var \Rcm\Repository\Page */
    protected $pageRepo;

    protected $pageType = 't';

    /** @var  \Rcm\Entity\Site */
    protected $site;

    /**
     * Constructor
     *
     * @param Site     $currentSite Current Site
     * @param PageRepo $pageRepo    Rcm Page Repo
     */
    public function __construct(
        Site     $currentSite,
        PageRepo $pageRepo
    ) {
        $this->pageRepo = $pageRepo;
        $this->site = $currentSite;

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
     * @param Site $site Site for validation
     *
     * @return void
     */
    public function setSite(Site $site)
    {
        $this->site = $site;
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
        $this->setValue($value);

        $check = $this->pageRepo->findOneBy(
            array(
                'pageId' => $value,
                'pageType' => $this->pageType,
                'site' => $this->site
            )
        );

        if (empty($check)) {
            $this->error(self::PAGE_TEMPLATE);

            return false;
        }

        return true;
    }
}
