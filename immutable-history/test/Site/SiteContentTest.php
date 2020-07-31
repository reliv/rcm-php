<?php

namespace Rcm\ImmutableHistory\Test\Site;

use PHPUnit\Framework\TestCase;
use Rcm\ImmutableHistory\Site\SiteContent;

class SiteContentTest extends TestCase
{
    public function testConstructAndToArrayForLongTermStorage()
    {
        $data = [
            'status' => 'A',
            'countryIso3' => 'USA',
            'languageId' => 27,
            'theme' => 'FUN THEME YEAH!',
            'siteTitle' => 'I\'m a fyuuuuuuun title!!!@#!($%821da1"',
            'faviconUrl' => '/fun/funfun.ico',
            'contentSchemaVersion' => 2,
            'loginPage' => '/login',
            'notAuthorized' => '/not-authorized',
            'notFound' => '/not-found',
            'siteLayout' => 'default'
        ];

        $unit = new SiteContent(
            $data['status'],
            $data['countryIso3'],
            $data['languageId'],
            $data['theme'],
            $data['siteTitle'],
            $data['faviconUrl'],
            $data['loginPage'],
            $data['notAuthorizedPage'],
            $data['notFoundPage'],
            $data['siteLayout']
        );

        $this->assertEquals($data, $unit->toArrayForLongTermStorage());
    }
}
