<?php

namespace Rcm\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Rcm\Entity\Country;
use Rcm\Entity\Language;
use Rcm\Exception\SiteNotFoundException;
use Zend\Cache\Storage\StorageInterface;

class SiteManager
{
    protected $domainManager;
    protected $entityManager;
    protected $cache;
    protected $currentSiteId;
    protected $currentSiteInfo;
    protected $currentSiteCountry;
    protected $currentSiteLanguage;

    public function __construct(
        DomainManager $domainManager,
        EntityManagerInterface $entityManager,
        StorageInterface $cache
    ) {
        $this->domainManager = $domainManager;
        $this->entityManager = $entityManager;
        $this->cache = $cache;
    }

    public function getCurrentSiteInfo()
    {
        if (!empty($this->currentSiteInfo)) {
            return $this->currentSiteInfo;
        }

        $currentSiteId = $this->getCurrentSiteId();

        if ($this->cache->hasItem('rcm_site_info_'.$currentSiteId)) {
            $this->currentSiteInfo = $this->cache->getItem('rcm_site_info_'.$currentSiteId);
            return $this->currentSiteInfo;
        }

        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('
            partial site.{
                owner,
                theme,
                status,
                favIcon,
                loginPage,
                siteLayout,
                siteTitle,
                siteId
            },
            language,
            country
        ')->from('\Rcm\Entity\Site', 'site')
            ->join('site.country', 'country')
            ->join('site.language', 'language')
            ->where('site.siteId = :siteId')
            ->setParameter('siteId', $currentSiteId);

        $this->currentSiteInfo = $queryBuilder->getQuery()->getSingleResult(Query::HYDRATE_ARRAY);

        $this->cache->setItem('rcm_site_info_'.$currentSiteId, $this->currentSiteInfo);

        return $this->currentSiteInfo;
    }

    public function getCurrentSiteLoginPage()
    {
        $siteInfo = $this->getCurrentSiteInfo();

        return $siteInfo['loginPage'];
    }

    public function getCurrentSiteTheme()
    {
        $siteInfo = $this->getCurrentSiteInfo();

        return $siteInfo['theme'];
    }

    public function getCurrentSiteDefaultLayout()
    {
        $siteInfo = $this->getCurrentSiteInfo();

        return $siteInfo['siteLayout'];
    }

    public function getCurrentSiteId()
    {
        if (empty($this->currentSiteId)) {
            $this->currentSiteId = $this->getCurrentSiteIdFromDomain();
        }

        return $this->currentSiteId;
    }

    public function getCurrentSiteCountry()
    {

        if (empty($this->currentSiteCountry) || !is_a($this->currentSiteCountry, '\Rcm\Entity\Country')) {
            $siteInfo = $this->getCurrentSiteInfo();

            $country = new Country();
            $country->setCountryName($siteInfo['country']['countryName']);
            $country->setIso2($siteInfo['country']['iso2']);
            $country->setIso3($siteInfo['country']['iso3']);

            $this->currentSiteCountry = $country;
        }

        return $this->currentSiteCountry;

    }

    public function getCurrentSiteLanguage()
    {
        if (empty($this->currentSiteLanguage) || !is_a($this->currentSiteLanguage, '\Rcm\Entity\Language')) {
            $siteInfo = $this->getCurrentSiteInfo();

            $language = new Language();
            $language->setIso6391($siteInfo['language']['iso639_1']);
            $language->setIso6392b($siteInfo['language']['iso639_2b']);
            $language->setIso6392t($siteInfo['language']['iso639_2t']);
            $language->setOldWebLanguage($siteInfo['language']['iso639_2t']);
            $language->setLanguageName($siteInfo['language']['languageName']);

            $this->currentSiteLanguage = $language;
        }

        return $this->currentSiteLanguage;
    }

    protected function getCurrentSiteIdFromDomain()
    {
        $domainList = $this->domainManager->getDomainList();

        $currentDomain = $_SERVER['HTTP_HOST'];

        if (empty($domainList[$currentDomain]) || empty($domainList[$currentDomain]['siteId'])) {
            throw new SiteNotFoundException('No site found for request domain: '.$_SERVER['HTTP_HOST']);
        }

        return $domainList[$currentDomain]['siteId'];
    }
}