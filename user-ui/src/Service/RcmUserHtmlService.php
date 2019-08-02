<?php

namespace RcmUser\Ui\Service;

use Zend\View\Renderer\PhpRenderer;
use Zend\View\Renderer\RendererInterface;

/**
 * Class RcmUserHtmlService
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class RcmUserHtmlService
{
    /**
     * @var array
     */
    protected $htmlAssets = [];

    /**
     * @var bool
     */
    protected $isDefaultHeaderBuilt = false;

    /**
     * RcmUserHtml constructor.
     *
     * @param $config
     */
    public function __construct(
        $config
    ) {
        $this->htmlAssets = $config['RcmUser\\Ui']['htmlAssets'];
    }

    /**
     * buildDefaultHtmlHead - Will only build once
     *
     * @param RendererInterface|PhpRenderer $view
     *
     * @return void
     */
    public function buildDefaultHtmlHead(RendererInterface $view)
    {
        if (!$this->isDefaultHeaderBuilt) {
            foreach ($this->htmlAssets as $type => $paths) {
                foreach ($paths as $path) {
                    $this->includeHead(
                        $view,
                        $type,
                        $path
                    );
                }
            }

            $this->isDefaultHeaderBuilt = true;
        }
    }

    /**
     * includeHead
     *
     * @param RendererInterface|PhpRenderer $view
     * @param string                        $type
     * @param string                        $path
     *
     * @return void
     */
    public function includeHead(
        RendererInterface $view,
        $type,
        $path
    ) {
        switch ($type) {
            case 'css':
                $view->headLink()->appendStylesheet($path);
                break;
            case 'js':
                $view->headScript()->appendFile($path);
                break;
        }
    }
}
