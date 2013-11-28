<?php

namespace RcmTest\Base;

require_once __DIR__ . '/BaseTestCase.php';
require_once __DIR__ . '/PluginTestCaseInterface.php';

use Rcm\Entity\Country;
use Rcm\Entity\Language;
use RcmTest\Base\BaseTestCase;

class PluginTestCase
    extends BaseTestCase
    implements PluginTestCaseInterface
{
    public function setUp()
    {
        parent::setUp();
    }

    public function createDefaultSiteInstance()
    {
        $country = $this->getDefaultCountry();
        $language = $this->getDefaultLanguage();


    }

    public function getDefaultCountry()
    {
        $country = new Country();
        $country->setCountryName('United States');
        $country->setIso2('US');
        $country->setIso3('USA');
        $this->entityManager->persist($country);
        $this->entityManager->flush();

        return $country;
    }

    public function getDefaultLanguage()
    {
        $language = new Language();
        $language->setLanguageName('English');
        $language->setIso6391('en');
        $language->setIso6392b('eng');
        $language->setIso6392t('eng');

        $this->entityManager->persist($language);
        $this->entityManager->flush();

        return $language;
    }
}