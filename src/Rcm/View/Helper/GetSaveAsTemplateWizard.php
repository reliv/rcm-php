<?php
    /**
     * Get the required html/js/css for the content manager.
     *
     * Contains the view helper to add he required html/js/css for the content manager.  Must be included in all
     * layouts for the CMS to work correctly.
     *
     * PHP version 5.3
     *
     * LICENSE: No License yet
     *
     * @category  Reliv
     * @package   Common\View\Helper
     * @author    Westin Shafer <wshafer@relivinc.com>
     * @copyright 2012 Reliv International
     * @license   License.txt New BSD License
     * @version   GIT: <git_id>
     * @link      http://ci.reliv.com/confluence
     */

namespace Rcm\View\Helper;

use \Zend\View\Helper\AbstractHelper;

/**
 * Get the required html/js/css for the content manager.
 *
 * Contains the view helper to add he required html/js/css for the content manager.  Must be included in all
 * layouts for the CMS to work correctly.
 *
 * @category  Reliv
 * @package   Common\View\Helper
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class GetSaveAsTemplateWizard extends AbstractHelper
{
    /**
     * Function called when using $this->view->getRcmRequired().  Will
     * call method getRequired.  See method getRequired
     * for more info
     *
     * @return self
     */
    public function __invoke()
    {
        return $this->getSaveAsTemplateWizard();
    }

    public function getSaveAsTemplateWizard()
    {
        return <<<EOL
<div id="rcmSaveTemplateWizard" style="position: absolute; left: -999999px;">
    <form action="" method="post" id="newPageWizard">
        <div id="rcmSaveTemplateError" style="display: none; text-align: center;">

        </div>

        <div id="newSaveTemplateIndicator" style="float: right;">&nbsp;</div>
        <p>Template Name:
            <input class="rcmTemplateNameInput"
               type="text"
               name="rcmTemplateName"
               id="rcmTemplateNameInput" value=""
            />

        </p>

        <p>&nbsp;</p>
    </form>
</div>
EOL;

    }
}