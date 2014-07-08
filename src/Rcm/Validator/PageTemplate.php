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

use Rcm\Service\PageManager;
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

    /** @var \Rcm\Service\PageManager */
    protected $pageManager;

    protected $pageType = 't';

    protected $siteId = null;

    /**
     * Constructor
     *
     * @param PageManager $pageManager Rcm Page Manager
     */
    public function __construct(PageManager $pageManager)
    {
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
        $this->setValue($value);

        if (!$this->pageManager->getPageById(
            $value,
            $this->pageType,
            $this->siteId
        )
        ) {
            $this->error(self::PAGE_TEMPLATE);
            return false;
        }

        return true;
    }
}