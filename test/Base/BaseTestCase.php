<?php

namespace RcmTest\Base;

require_once __DIR__ . '/Zf2TestCase.php';

use Rcm\Entity\Country;
use Rcm\Entity\Language;
use Rcm\Entity\Site;

class BaseTestCase extends Zf2TestCase
{

    public function getSiteMock()
    {
        $site = new Site();
        $site->setLanguage($this->getLanguageMock());
        $site->setCountry($this->getCountryMock());
    }

    public function getCountryMock()
    {
        $country = new Country();
        $country->setCountryName('United States');
        $country->setIso2('US');
        $country->setIso3('USA');

        return $country;
    }

    public function getLanguageMock()
    {
        $language = new Language();
        $language->setLanguageName('English');
        $language->setIso6391('en');
        $language->setIso6392b('eng');
        $language->setIso6392t('eng');

        return $language;
    }

    /**
     * Prevent warning when testing individual suites like:
     * No tests found in class "RcmTest\Base\Zf2TestCase".
     */
    public function testNothing(){
        $this->assertTrue(true);
    }
}