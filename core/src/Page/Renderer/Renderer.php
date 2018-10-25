<?php

namespace Rcm\Page\Renderer;

use Rcm\Page\PageData\PageData;

/**
 * @GammaRelease
 *
 * Interface Renderer
 * @package     Rcm\Entity
 */
interface Renderer
{
    /**
     * __invoke
     *
     * @param PageData $pageData
     *
     * @return string Rendered Html
     */
    public function __invoke(PageData $pageData);
}
