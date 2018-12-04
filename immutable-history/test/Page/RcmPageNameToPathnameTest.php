<?php

namespace Rcm\ImmutableHistory\Test\Page;

use PHPUnit\Framework\TestCase;
use Rcm\ImmutableHistory\Page\RcmPageNameToPathname;

class RcmPageNameToPathnameTest extends TestCase
{
    public function testWithNormalPage()
    {
        $unit = new RcmPageNameToPathname();
        $this->assertEquals(
            '/very-very-fun-normal-page',
            $unit->__invoke('very-very-fun-normal-page', 'n')
        );
    }

    public function testWithProductPage()
    {
        $unit = new RcmPageNameToPathname();
        $this->assertEquals(
            '/p/very-very-fun-product',
            $unit->__invoke('very-very-fun-product', 'p')
        );
    }
}
