<?php

namespace Rcm\ImmutableHistory\Page;

class RcmPageNameToPathname
{
    public function __invoke(string $pageName, string $pageType)
    {
        if ($pageName === 'index' && $pageType === 'n') {
            return '/';
        }

        return '/' . ($pageType !== 'n' ? $pageType . '/' : '') . $pageName;
    }
}
