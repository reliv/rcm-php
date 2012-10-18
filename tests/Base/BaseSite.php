<?php
/**
 * Created by JetBrains PhpStorm.
 * User: westin
 * Date: 7/8/12
 * Time: 2:16 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Rcm\Base;

require_once 'BaseTest.php';

class BaseSite extends BaseTest
{

    protected $siteWideInstances;

    protected function getLanguageData()
    {
        return array(
            'languageId' => 4,
            'iso6392t' => 'eng',
            'iso6392b' => 'spn',
            'iso6391' => 'en'
        );
    }

    protected function getDomainData()
    {
        return array(
            'id' => 8,
            'domainName' => 'unittest.com',
        );
    }

    protected function getPrimaryDomainData()
    {
        return array(
            'id' => 1,
            'domainName' => 'www.unittest.com',
        );
    }

    protected function getPwsData()
    {
        return array(
            'pwsId' => 7,
            'site' => new \Rcm\Entity\Site(),
            'activeDate' => new \DateTime('2012-07-08 11:14:15'),
            'cancelDate' => new \DateTime('2010-07-08 11:14:15'),
            'lastUpdated' => new \DateTime('2011-07-08 11:14:15'),
        );
    }

    protected function getCountryData()
    {
        return array(
            'countryId' => 87,
            'iso2' => 'US',
            'iso3' => 'USA',
        );
    }

    protected function getPluginInstanceData()
    {
        return array(
            0 => array(
                'instanceId' => 7892432,
                'layoutContainer' => 2,
                'renderOrderNumber' => 8,
                'siteWide' => true,
                'siteWideName' => 'Site Wide Plugin Test',
            ),

            1 => array(
                'instanceId' => 5556121,
                'layoutContainer' => 1,
                'renderOrderNumber' => 0,
                'siteWide' => false,
            ),

            2 => array(
                'instanceId' => 5556121,
                'layoutContainer' => 1,
                'renderOrderNumber' => 1,
                'siteWide' => false,
            )
        );
    }

    protected function getPageRevisionData()
    {
        return array(
            0 => array(
                'revisionId' => 9467,
                'author' => 900075,
                'description' => 'Unit Test Revision One',
                'keywords' => 'Unit, Testing',
                'pageLayout' => 'SomeLayout',
                'pageTitle' => 'Revision One Page Title',
            ),

            0 => array(
                'revisionId' => 98556,
                'author' => 45656,
                'description' => 'Unit Test Revision Two',
                'keywords' => 'Unit, Testing, Revision, Two',
                'pageLayout' => 'SomeLayout',
                'pageTitle' => 'Revision Two Page Title',
            ),

            1 => array(
                'revisionId' => 98556,
                'author' => 45656,
                'description' => 'Unit Test Revision Three',
                'keywords' => 'Unit, Testing, Revision, Three',
                'pageLayout' => 'SomeLayout',
                'pageTitle' => 'Revision Three Page Title',
            ),
        );
    }

    public function getPageData()
    {
        return array (
            0 => array(
                'pageId' => 231,
                'author' => 7444125,
                'name' => 'myPageOne',
            ),
            1 => array(
                'pageId' => 9167,
                'author' => 389023,
                'name' => 'myPageTwo'
            )
        );
    }

    protected function getPageEntitiesForTests()
    {
        $count = 0;

        foreach ($this->getPluginInstanceData() as $instance) {
            $instances[$count] = $this->getPluginInstance(
                $instance,
                'RcmHtmlArea'
            );

            if ($instance['siteWide'] === true) {
                $this->siteWideInstances[$count] = $instances[$count];
            }

            $count++;
        }

        foreach ($this->getPageRevisionData() as $revision) {
            $pageRevisions[] = $this->getPageRevision($revision, $instances);
        }

        foreach ($this->getPageData() as $page) {
            $pages[] = $this->getPage($page, $pageRevisions);
        }

        return $pages;
    }

    protected function getSiteEntityForTests()
    {
        $site = new \Rcm\Entity\Site();

        $language = $this->getLanguage($this->getLanguageData());

        $country = $this->getCountry($this->getCountryData());

        $domain = $this->getDomain($this->getDomainData(), $language);
        $primaryDomain = $this->getDomain(
            $this->getPrimaryDomainData(),
            $language
        );

        $domain->setPrimary($primaryDomain);
        $primaryDomain->setAdditionalDomain($domain);

        foreach ($this->getPageEntitiesForTests() as $page) {
            $site->addPage($page);
        }

        /** @var \Rcm\Entity\PagePluginInstance $instance */
        foreach ($this->siteWideInstances as $instance) {
            $site->addSiteWidePlugin($instance->getInstance());
        }

        $site->setCountry($country);
        $site->setLanguage($language);
        $site->setSiteId(55);
        $site->setDomain($primaryDomain);
        $site->setOwner('445667');
        $site->setPwsInfo(
            $this->getPwsInfo($this->getPwsData(), $site)
        );
        $site->setStatus('A');

        return $site;
    }

    protected function getPluginInstance($data, $plugin)
    {
        $instance = new \Rcm\Entity\PluginInstance();
        $instance->setInstanceId($data['instanceId']);
        $instance->setPlugin($plugin);

        if ($data['siteWide'] && !empty($data['siteWideName'])) {
            $instance->setSiteWide();
            $instance->setDisplayName($data['siteWideName']);
        }

        $pageInstance = new \Rcm\Entity\PagePluginInstance();
        $pageInstance->setRenderOrderNumber($data['renderOrderNumber']);
        $pageInstance->setLayoutContainer($data['layoutContainer']);
        $pageInstance->setPageInstanceId($data['instanceId']);
        $pageInstance->setInstance($instance);

        return $pageInstance;
    }

    protected function getLanguage($data)
    {
        $language = new \Rcm\Entity\Language();
        $language->setLanguageId($data['languageId']);
        $language->setIso6392t($data['iso6392t']);
        $language->setIso6391($data['iso6391']);

        return $language;
    }

    protected function getCountry($data)
    {
        $country = new \Rcm\Entity\Country();
        $country->setIso2($data['iso2']);
        $country->setIso3($data['iso3']);

        return $country;
    }

    public function getDomain($data, $language)
    {
        $domain = new \Rcm\Entity\Domain();
        $domain->setId($data['id']);
        $domain->setDomainName($data['domainName']);
        $domain->setDefaultLanguage($language);

        return $domain;
    }

    public function getPwsInfo($data, $site)
    {
        $pwsInfo = new \Rcm\Entity\PwsInfo();
        $pwsInfo->setPwsId($data['pwsId']);
        $pwsInfo->setActiveDate($data['activeDate']);
        $pwsInfo->setCancelDate($data['cancelDate']);
        $pwsInfo->setLastUpdated($data['lastUpdated']);
        $pwsInfo->setSite($site);

        return $pwsInfo;
    }

    public function getPageRevision($data, $plugins)
    {
        $pageRevision = new \Rcm\Entity\PageRevision();
        $pageRevision->setPageRevId($data['revisionId']);
        $pageRevision->setAuthor($data['author']);
        $pageRevision->setDescription($data['description']);
        $pageRevision->setKeywords($data['keywords']);
        $pageRevision->setPageLayout($data['pageLayout']);
        $pageRevision->setPageTitle($data['pageTitle']);

        foreach ($plugins as $plugin) {
            $pageRevision->addInstance($plugin);
        }

        return $pageRevision;
    }

    public function getPage($data, $pageRevisions)
    {
        $page = new \Rcm\Entity\Page();
        $page->setPageId($data['pageId']);
        $page->setAuthor($data['author']);
        $page->setName($data['name']);
        $page->setCurrentRevision($pageRevisions[0]);

        foreach ($pageRevisions as $revision) {
            $page->addPageRevision($revision);
        }

        return $page;
    }
}