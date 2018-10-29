<?php

namespace Rcm\ImmutableHistory\Page;

class RcmPageNameToPathname
{
    //@TODO take page type and '/p/whatever' pages into account!
    public function __invoke($pageName)
    {
        if ($pageName === 'index') {
            return '/';
        }

        return '/' + $pageName;
    }
}
