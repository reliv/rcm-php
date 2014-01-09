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
 *
 */
class RcmViewInit extends AbstractHelper
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
        return $this->init();
    }

    public function init()
    {
        /** @var \Zend\View\Renderer\PhpRenderer $renderer */
        $renderer = $this->getView();
        $renderer->rcmTop = '';
        $renderer->rcmBottom = '';
        $renderer->containers = array();

        $this->setHead($renderer);
        $this->initPlugins($renderer);

        if ($renderer->adminIsLoggedIn) {
            $this->getRequiredAdmin($renderer);
        }

        return $this;
    }

    public function getBodyTop() {
        return $this->bodyTop;
    }

    public function getBodyBottom() {
        return $this->bodyBottom;
    }

    protected function getRequiredAdmin(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        $this->setAdminHead($renderer);
        $this->getAdminInitScript($renderer);
        $this->getAdminTopBody($renderer);
        $this->getAdminBottomBody($renderer);
    }

    protected function setHead(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        $this->setMeta($renderer);
        $this->setJs($renderer);
        $this->setCss($renderer);
    }

    protected function setMeta(\Zend\View\Renderer\PhpRenderer $renderer)
    {

        $renderer->headMeta()->appendName('description', $renderer->metaDesc);

        $renderer->headMeta()->appendName('keywords', $renderer->metaKeys);
    }

    protected function setJs(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        // HTML5 shim, for IE6-8 support of HTML elements
        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/js/html5.js', 'text/javascript',
            array('conditional' => 'lt IE 9',)
        );

        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/modules/rcm/vendor/jquery-ui/js/jquery-1.9.1.js', 'text/javascript'
        );

        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/modules/rcm/vendor/jquery-ui/js/jquery-ui-1.10.3.custom.min.js', 'text/javascript'
        );

        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/modules/rcm/vendor/jquery-block-ui/jquery.blockUI.js', 'text/javascript'
        );

        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/modules/rcm/vendor/jquery-formatcurrency/jquery.format-currency-1.4.0.min.js', 'text/javascript'
        );

        $script = '$.blockUI.defaults.baseZ = 2000; $.blockUI.defaults.applyPlatformOpacityRules = false;';

        $renderer->headScript()->appendScript($script,'text/javascript');


    }

    protected function setCss(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        $renderer->headLink()->appendStylesheet(
            $renderer->basePath() . '/modules/rcm/vendor/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.min.css'
        );
    }

    protected function setAdminHead(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        $this->setAdminMeta($renderer);
        $this->setAdminJs($renderer);
        $this->setAdminCss($renderer);
        $this->getAdminRichEditor($renderer);
        $this->getPluginAdminEditJs($renderer);

    }

    protected function setAdminMeta(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        return;
    }

    protected function setAdminJs(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/modules/rcm/js/admin/config.js',
            'text/javascript'
        );

        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/modules/rcm/js/admin/content-manager2.js',
            'text/javascript'
        );

        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/modules/rcm/vendor/prompt-helper/prompt-helper-legacy-deprecated.js',
            'text/javascript'
        );

        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/modules/rcm/vendor/jquery-ui-alert-confirm/jquery-ui-alert-confirm.js',
            'text/javascript'
        );

        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/modules/rcm/vendor/jquery-dialog-inputs/jquery-dialog-inputs.js',
            'text/javascript'
        );

        $renderer->headScript()->appendFile(
            $renderer->basePath()
                .'/modules/rcm/vendor/medialize-jquery-context-menu/src/jquery.contextMenu.js',
            'text/javascript'
        );

        $renderer->headScript()->appendFile(
            $renderer->basePath()
                . '/modules/rcm/vendor/ioncache-tag-handler/js/jquery.taghandler.min.js',
            'text/javascript'
        );

        $renderer->headScript()->appendFile(
            $renderer->basePath()
                .'/modules/rcm/vendor/medialize-jquery-context-menu/src/jquery.contextMenu.js',
            'text/javascript'
        );
    }

    protected function setAdminCss(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        $renderer->headLink()->appendStylesheet(
            $renderer->basePath(). '/modules/rcm/css/admin/admin-toolbar.css'
        );

        $renderer->headLink()->appendStylesheet(
            $renderer->basePath(). '/modules/rcm/css/admin/cm-admin.css'
        );

        $renderer->headLink()->appendStylesheet(
            $renderer->basePath()
                .'/modules/rcm/vendor/medialize-jquery-context-menu/src/jquery.contextMenu.css'
        );

        $renderer->headLink()->appendStylesheet(
            $renderer->basePath()
                .'/modules/rcm/vendor/ioncache-tag-handler/css/jquery.taghandler.css'
        );
    }

    protected function getAdminRichEditor(\Zend\View\Renderer\PhpRenderer $renderer)
    {

        if ($renderer->adminRichEditor == 'tinyMce') {
            return $this->getTinyMceEditor($renderer);
        } elseif ($renderer->adminRichEditor == 'aloha') {
            return $this->getAlohaEditor($renderer);
        } else {
            return $this->getCkEditor($renderer);
        }

    }

    protected function getTinyMceEditor(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        $renderer->headScript()->appendFile(
            $renderer->basePath()
                . '/modules/rcm/vendor/tinymce/jscripts/tiny_mce/jquery.tinymce.js',
            'text/javascript'
        );



        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/modules/rcm/js/admin/rcm-tinymce.js',
            'text/javascript'
        );


        $renderer->headScript()->appendScript(
            'var rcmEditor = new RcmTinyMceEditor(rcmTinyMceConfig);',
            'text/javascript'
        );
    }

    protected function getAlohaEditor(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        $renderer->headLink()->appendStylesheet(
            $renderer->basePath(). '/modules/rcm/vendor/aloha/css/aloha.css'
        );

        $renderer->headScript()->setAllowArbitraryAttributes(true)->appendFile(
            $renderer->basePath() . '/modules/rcm/vendor/aloha/lib/require.js',
            'text/javascript',
            array(
                'data-aloha-plugins'
                => 'common/ui,common/format,common/highlighteditables,common/link'
            )
        );

        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/modules/rcm/vendor/aloha/lib/aloha.js',
            'text/javascript'
        );

        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/modules/rcm/js/admin/rcm-aloha.js',
            'text/javascript'
        );

        $renderer->headScript()->appendScript(
            'var rcmEditor = new RcmAlohaEditor("");',
            'text/javascript'
        );
    }

    protected function getCkEditor(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/modules/rcm/vendor/ckeditor/ckeditor.js',
            'text/javascript'
        );

        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/modules/rcm/js/admin/rcm-ckeditor.js',
            'text/javascript'
        );

        $renderer->headScript()->appendScript(
            'var rcmEditor = new RcmCkEditor(rcmCkConfig);',
            'text/javascript'
        );
    }

    protected function getPluginAdminEditJs(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        $hasPageJs = array();

        foreach($renderer->plugins as $container => $orders){
            /** @var \Rcm\Entity\PluginInstance $pluginInstance */
            foreach ($orders as $pluginInstance) {
                $pluginEntity = $pluginInstance['plugin'];
                if ($pluginEntity->hasAdminJs()) {
                    $renderer->headScript()->appendFile(
                        $renderer->basePath() . $pluginEntity->getAdminEditJs(),
                        'text/javascript'
                    );

                    $hasPageJs[$pluginEntity->getName()] = $pluginEntity->getName();

                }

                if ($pluginEntity->hasAdminCss()) {
                    $renderer->headLink()->appendStylesheet(
                        $renderer->basePath() . $pluginEntity->getAdminEditCss()
                    );
                }
            }
        }
    }

    protected function getAdminInitScript(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        $script = "
            $(function(){
                window.rcmEdit = new RcmEdit(rcmConfig);
                rcmEdit.setLanguage('".$renderer->language."');
                rcmEdit.setPage('".$renderer->page->getName()."');
                rcmEdit.setPageRevision('".$renderer->pageRevision."');
                rcmEdit.setPageType('".$renderer->pageType."')
                rcmEdit.setNewInstanceId(".$renderer->newPluginCount.");
                rcmEdit.setEditor(rcmEditor);
                rcmEdit.setNewPluginInstanceAjaxPath('".$renderer->url(
                    'contentManagerNewInstanceAjax'
                )."');
                rcmEdit.init();
            });
        ";

        $renderer->headScript()->appendScript($script,'text/javascript');
    }

    protected function getAdminTopBody(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        /** @var \Rcm\Entity\Page $page */
        $page = $renderer->page;

        $pageRevision = $renderer->pageRevision;

        $currentRevision = $page->getCurrentRevision();

        $publishButton = '';

        if (empty($currentRevision) || $pageRevision != $currentRevision->getPageRevId()) {
            $publishButton ='<a href="'.$renderer->publishButtonHref.'" class="rcmPublishButton">Publish</a>';
        }

        $this->appendBodyTop('<div id="rcmAdminPagePopoutWindow"></div>');
        $this->appendBodyTop('
            <div id="ContentManagerTopAdminPanel">

                <div id="rcmAdminMenuBar">
                    '.$renderer->adminTitleBar($page, $pageRevision, $renderer->language) .'

                    <ul id="rcmAdminMenu">
                        '.$renderer->addAdminNavigation($renderer->adminPanel).'
                    </ul>

                    <div class="editSaveCancel">

                        <div id="rcmAdminToolbarEdit">
                            '.$publishButton.'
                            <a href="#" class="rcmEditButton">Edit</a>
                        </div>

                        <div id="rcmAdminToolbarSaveCancel">
                            <a href="#" class="rcmSaveButton">Save Draft</a>
                            <a href="#" class="rcmCancelButton">Cancel</a>
                        </div>

                        <div id="rcmAdminToolbarPleaseWait">
                            <p>Please wait...</p>
                        </div>
                    </div>
                </div>
                <div id="ckEditortoolbar"></div>

            </div>

            <div id="ToolBarSpacer"></div>

            <div id="rcmLayoutEditorContainer">
                <div id="rcmLayoutEditorColumn">
                    '.$renderer->renderLayoutEditorContainers($renderer->layoutContainers).'
                </div>
            </div>

            <div id="RcmRealPage">
        ');


    }

    protected function getAdminBottomBody()
    {
        $this->appendBodyBottom('
            </div>


            <div id="ckEditorfooter"></div>
        ');


    }

    private function appendBodyTop($value)
    {
        $renderer = $this->getView();
        $renderer->rcmTop .= $value;
    }

    private function appendBodyBottom($value)
    {
        $renderer = $this->getView();
        $renderer->rcmBottom .= $value;
    }

    /**  Init Plugins */
    public function initPlugins(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        /** @var \Zend\View\Helper\ViewModel $helper  */
        $helper = $renderer->plugin('view_model');
        $view = $helper->getCurrent();
        $renderedContainers = array();
        $containers  =  $renderer->plugins;

        if (!empty($containers) && is_array($containers) ) {
            /** @var \Rcm\Entity\PagePluginInstance $plugin */
            foreach ($containers as $containerNum => $plugins) {
                foreach ($plugins as $plugin) {
                    $renderedContainers[$containerNum][] = $renderer->renderPlugin($plugin['plugin'], $plugin['height'], $plugin['width'], $plugin['float']);
                }
            }
        }
        $renderer->renderedContainers = $renderedContainers;
        $helper->setCurrent($view);

    }
}