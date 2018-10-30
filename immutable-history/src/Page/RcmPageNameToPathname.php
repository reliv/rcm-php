<?php

namespace Rcm\ImmutableHistory\Page;

class RcmPageNameToPathname
{
    //@TODO take page type and '/p/whatever' pages into account!
    public function __invoke(string $pageName, string $pageType)
    {
        if ($pageName === 'index' && $pageType === 'n') {
            return '/';
        }

        return '/' . ($pageType !== 'n' ? $pageType . '/' : '') . $pageName;
    }
}
