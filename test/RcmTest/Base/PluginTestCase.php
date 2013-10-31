<?php

namespace RcmTest\Base;

use Rcm\Entity\Country;
use Rcm\Entity\Language;

class PluginTestCase extends DoctrineTestCase
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