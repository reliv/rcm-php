<?php


namespace Rcm\View\Helper;


/**
 * Class HeadTitle
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class HeadTitle extends \Zend\View\Helper\HeadTitle
{
    /**
     * @var bool only set values once
     */
    protected $rcmSiteIsSet = false;

    /**
     * @var bool only set values once
     */
    protected $rcmPageIsSet = false;

    public function __construct()
    {
        $this->setSeparator(' - ');

        parent::__construct();
    }

    /**
     * Retrieve placeholder for title element and optionally set state
     *
     * @param  string $title
     * @param  string $setType
     *
     * @return HeadTitle
     */
    public function __invoke($title = null, $setType = null)
    {
        $this->setRcmSiteTitle();
        $this->setRcmPageTitle();

        return parent::__invoke($title, $setType);
    }

    /**
     * setRcmSiteTitle
     *
     * @return void
     */
    public function setRcmSiteTitle()
    {
        if ($this->rcmSiteIsSet) {
            return;
        }

        $view = $this->getView();

        if (empty($view->site)) {
            return;
        }

        $siteTitle = $view->site->getSiteTitle();

        if (!empty($siteTitle)) {
            $this->prepend(strip_tags($siteTitle));
        }

        $this->rcmSiteIsSet = true;
    }

    /**
     * setRcmPageTitle
     *
     * @return void
     */
    public function setRcmPageTitle()
    {
        if ($this->rcmPageIsSet) {
            return;
        }

        $view = $this->getView();

        if (empty($view->page)) {
            return;
        }

        $pageTitle = $view->page->getPageTitle();

        if (!empty($pageTitle)) {
            $this->append(strip_tags($pageTitle));
        }

        $this->rcmPageIsSet = true;
    }
}
