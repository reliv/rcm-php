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
class GetNewTemplateWizard extends AbstractHelper
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
        return $this->getNewTemplateWizard();
    }

    public function GetNewTemplateWizard()
    {
        $renderer = $this->getView();

        $html = '
            <div id="rcmNewFromTemplateWizard" style="position: absolute; left: -999999px;">
            <form action="" method="post" id="newPageWizard">
                <div id="rcmNewFromTemplateErrorLine" style="display: none; text-align: center;">

                </div>
                <table width="100%">
                    <tr>
                        <td valign="middle" width="150"><p> Page URL:</p></td>
                        <td valign="middle">
                            <input class="rcmNewFromTemplateInput"
                                   type="text"
                                   name="rcmNewFromTemplateUrl"
                                   id="rcmNewFromTemplateUrl" value=""
                                    />
                        </td>
                        <td valign="middle"><p id="rcmNewFromTemplateValidatorIndicator">&nbsp;</p></td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign="middle"><p>Page Title:</p></td>
                        <td valign="middle">
                            <input class="rcmNewFromTemplateInput"
                                   type="text"
                                   name="rcmNewFromTemplatePageName"
                                   id="rcmNewFromTemplateName"
                                   value=""
                                    />
                        </td>
                        <td valign="middle"></td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign="middle"><p>Select Template:</p></td>
                        <td valign="middle">
                            <select name="rcmPageRevision" id="rcmPageRevision">
        ';

        foreach ($renderer->rcmTemplates as $template) {
            $html .= '<option value="'.$template->getPublishedRevision()->getPageRevId().'">'.$template->getName().'</option>';
        }

        $html .= '<option value="-1">Blank Page (Experts Only)</option>';
        $html .= '
                            </select>
                        </td>
                        <td valign="middle"></td>
                    </tr>
                </table>
                <div id="rcmNewPageLayoutSelector">
                <p>&nbsp;</p>
        ';



        foreach ($renderer->newPageLayoutContainers as $newLayoutKey => $newLayout) {
            $html .= '


                <div class="rcmNewPageLayoutWrapper">
                    <div class="rcmNewPageLayoutContainer">
                        <div class="rcmNewPageLinkOverlay"></div>
                        <img src="'.$newLayout['screenShot'].'" /> <br />
                        '.$newLayout['display'].'
                        <input type="hidden" name="'.$newLayoutKey.'" value="N" class="rcmLayoutKeySelector" />
                    </div>
                </div>
            ';
        }



        $html .=  '
                <div style="clear: both;"></div>
                </div>
                <input type="hidden" name="rcmNewPageSelectedLayout" id="rcmNewPageSelectedLayout" value="" />
            </form>
            </div>
        ';

        return $html;
    }
}