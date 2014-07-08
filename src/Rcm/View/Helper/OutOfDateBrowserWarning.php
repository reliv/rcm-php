<?php
/**
 * Out of Date Browser Warning View Helper
 *
 * Out of Date Browser Warning View Helper
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Rcm\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Out of Date Browser Warning View Helper
 *
 * Out of Date Browser Warning View Helper
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class OutOfDateBrowserWarning extends AbstractHelper
{
    protected $lowestIeVersion = 8;

    /**
     * Invoke magic method
     *
     * @return string
     */
    public function __invoke()
    {
        /* @codingStandardsIgnoreStart */

        return '
        <!--[if lt IE ' . $this->lowestIeVersion . ']>
            <div style="text-align:center;width:100%;padding:5px 10px;z-index:9999;background-color:#FDF2AB;position:absolute;border-bottom:1px solid #A29330">
                ' . $this->getMessage() . '
            </div>
        <![endif]-->
        ';
        /* @codingStandardsIgnoreEnd */
    }

    /**
     * Get Browser warning message
     *
     * @return string
     */
    public function getMessage()
    {
        return
            'This website does not work with Internet Explorer versions below '
            . $this->lowestIeVersion . '. Please update your browser
            or install the latest Chrome or Firefox web browser.';
    }
}
