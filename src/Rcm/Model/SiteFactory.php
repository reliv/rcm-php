<?php

/**
 * Site Factory
 *
 * Returns a site object for a given country and language
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm\Model\
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */

namespace Rcm\Model;

use Rcm\Entity\Domain;
use Rcm\Entity\PwsInfo;
use Rcm\Model\EntityMgrAware,
    Doctrine\ORM\EntityManager,
    \Rcm\Exception\LanguageNotFoundException,
    \Rcm\Exception\SiteNotFoundException,
    Rcm\Entity\Site,
    \Rcm\Entity\Country,
    \Rcm\Entity\Language;

/**
 * Site Factory
 *
 * Returns a site object for a given country and language
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm\Model\
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: Release: 1.0
 * @link      http://ci.reliv.com/confluence
 */

class SiteFactory extends EntityMgrAware
{
    /**
     * @var \Doctrine\ORM\EntityManager entity manager
     */
    protected $entityMgr;

    public function getAvailableSites()
    {
        $entityMgr = $this->entityMgr;
        return $entityMgr->getRepository('\Rcm\Entity\Site')->findAll();
    }

    /**
     * Get the site entity for a given domain name and language. Will fall back
     * to a domainName's default language if necessary.
     *
     * @param string $domainName site domain name
     * @param string $language   site language
     *
     * @return Site
     * @throws SiteNotFoundException
     */
    public function getSite($domainName, $language = null)
    {
        $entityMgr = $this->entityMgr;
        $siteRepo = $entityMgr->getRepository('\Rcm\Entity\Site');

        $domain = $this->getDomain($domainName);

        //Get Language Entity. Fall back to default if needed
        $languageEntity = '';
        if (isset($language)) {
            try {
                $languageEntity = $this->getLanguage($language);
            } catch (LanguageNotFoundException $e) {
            }
        }
        if (!$languageEntity) {
            $languageEntity = $domain->getDefaultLanguage();
        }

        //Get Site Entity
        $site = $siteRepo->findOneBy(
            array(
                'domain' => $domain,
                'language' => $languageEntity
            )
        );

        if (!$site) {
            throw new SiteNotFoundException(
                "No site found in DB for $domainName with language $language"
            );
        }

        //THIS SHOULD PROBABLY GO SOMEWHERE ELSE. BUT WHERE?
        //NEED FOR MONTH NAME TRANSLATIONS IN EVENT PLUGIN
        $iso6391 = $languageEntity->getIso6391();
        $localName = strtolower($iso6391) . '_' . strtoupper($site->getCountry()->getIso2()) . '.UTF-8';
        setlocale(LC_ALL, $localName);

        var_dump(\Locale::getDefault());

        return $site;
    }

    /**
     * Creates a new site entity
     *
     * @param string $domainName             domain name
     * @param string $theme                  Theme to use for the new site
     * @param \Rcm\Entity\Country $country                country
     * @param \Rcm\Entity\Language $language               language
     * @param integer $ownerAccountNum        owner account number
     * @param string $loginPageUrl           URL to login page
     * @param boolean $loginRequired          Require login for site access
     * @param array|string $permitteTypes          Account Type(s) needed to access site
     * @param array $additionalDomain       an additional domain that redirects
     *                                                     to this site
     * @param array $initialSiteWidePlugins Initial SiteWide plugins for the site.
     *
     * @return \Rcm\Entity\Site
     */
    public function createNewSite(
        $domainName,
        $theme,
        \Rcm\Entity\Country $country,
        \Rcm\Entity\Language $language,
        $ownerAccountNum,
        $initialSiteWidePlugins = array(),
        $additionalDomain = array(),
        $loginPageUrl = '',
        $loginRequired = false,
        $permittedTypes = array()
    ) {
        $entityMgr = $this->entityMgr;

        if ($loginRequired && (empty($permittedTypes) || empty($loginPageUrl))) {
            throw new \Exception('Site set to restricted, but no login page or permitted types provided');
        }

        //Check for existing domain
        try {
            $domain[0] = $this->getDomain($domainName);
        } catch (\Exception $e) {
            $domain[0] = new \Rcm\Entity\Domain();
            $domain[0]->setDomainName($domainName);
            $domain[0]->setDefaultLanguage($language);
        }

        if (!empty($additionalDomain) && is_array($additionalDomain)) {
            foreach ($additionalDomain as $key => $additionalDomainName) {
                try {
                    $this->getDomain($additionalDomainName);
                } catch (\Exception $e) {
                    $domain[$key + 1] = new \Rcm\Entity\Domain();
                    $domain[$key + 1]->setDomainName($additionalDomainName);
                    $domain[$key + 1]->setPrimary($domain[0]);
                    $domain[0]->setAdditionalDomain($domain[$key + 1]);
                    $entityMgr->persist($domain[$key + 1]);
                }
            }
        }

        $pwsInfo = new \Rcm\Entity\PwsInfo();
        $pwsInfo->setActiveDate(new \DateTime("now"));
        $pwsInfo->setLastUpdated(new \DateTime("now"));

        $site = new \Rcm\Entity\Site();
        $site->setDomain($domain[0]);
        $site->setOwner($ownerAccountNum);
        $site->setPwsInfo($pwsInfo);
        $site->setLanguage($language);
        $site->setCountry($country);
        $site->setStatus("A");
        $site->setTheme($theme);
        $site->setLoginRequired($loginRequired);
        $site->setLoginPage($loginPageUrl);
        $site->setCurrencySymbol('$');

        if (!empty($permittedTypes) && is_array($permittedTypes)) {
            $site->addPermittedAccountTypesByArray($permittedTypes);
        } elseif (!empty($permittedTypes) && !is_array($permittedTypes)) {
            $site->addPermittedAccountType($permittedTypes);
        }

        if (!empty($initialSiteWidePlugins)
            && is_array($initialSiteWidePlugins)
        ) {
            /** @var \Rcm\Entity\PagePluginInstance $sitePlugin */
            foreach ($initialSiteWidePlugins as $sitePlugin) {
                if ($sitePlugin instanceof \Rcm\Entity\PagePluginInstance) {
                    $site->addSiteWidePlugin($sitePlugin->getInstance());
                }
            }
        }

        $pwsInfo->setSite($site);

        $entityMgr->persist($site);
        $entityMgr->persist($domain[0]);
        $entityMgr->persist($pwsInfo);

        $entityMgr->flush();

        return $site;

    }

    public function cloneSite(Site $siteToClone, $newDomain, $newCountry, $newLanguage)
    {

        /** @var \Rcm\Entity\Language $siteLanguage */
        $siteLanguage = $this->entityMgr->getRepository('\Rcm\Entity\Language')->findOneBy(array(
            'iso639_2t' => $newLanguage
        ));

        if (empty($siteLanguage)) {
            $siteLanguage = $this->entityMgr->getRepository('\Rcm\Entity\Language')->findOneBy(array(
                'iso639_2b' => $newLanguage
            ));
        }

        if (empty($siteLanguage)) {
            throw new \Exception('Language ISO Three digit not found');
        }

        /** @var \Rcm\Entity\Country $siteCountry */
        $siteCountry = $this->entityMgr->getRepository('\Rcm\Entity\Language')->findOneBy(array(
            'iso3' => $newCountry
        ));

        if (empty($siteCountry)) {
            throw new \Exception('Country ISO Three digit not found');
        }

        /** @var \Rcm\Entity\Country $siteCountry */
        $siteDomain = $this->entityMgr->getRepository('\Rcm\Entity\Domain')->findOneBy(array(
            'domain' => $newDomain
        ));

        if (!empty($siteDomain)) {
            throw new \Exception('Domain already in use');
        }

        $domainToAdd = new Domain();
        $domainToAdd->setDomainName($newDomain);
        $domainToAdd->setDefaultLanguage($siteLanguage);

        $newSite = clone $siteToClone;
        $newSite->setDomain($domainToAdd);
        $newSite->setLanguage($siteLanguage);
        $newSite->setCountry($siteCountry);

        $this->entityMgr->persist($newSite);
        $this->entityMgr->flush();

    }

    /**
     * Gets the domain entity for a given domainName
     *
     * @param string $domainName domain name
     *
     * @return \Rcm\Entity\Domain
     * @throws \Rcm\Exception\RuntimeException
     * @throws \Rcm\Exception\InvalidArgumentException
     */
    public function getDomain($domainName)
    {
//
//        if (empty($domainName)) {
//            throw new \Rcm\Exception\DomainNotFoundException(
//                'No Domain Found.'
//            );
//        }


        $query = array('domain' => $domainName);
        $entityMgr = $this->entityMgr;
        $repo = $entityMgr->getRepository('\Rcm\Entity\Domain');
        $domainEntity = $repo->findOneBy($query);

        if (empty($domainEntity)) {
            throw new \Rcm\Exception\DomainNotFoundException(
                'No Domain Found in DB for: ' . $domainName
            );
        }

        if (!$domainEntity->isPrimary()) {
            return $domainEntity->getPrimary();
        }

        return $domainEntity;
    }

    /**
     * Gets the language entity for a given language string
     *
     * @param string $language language
     *
     * @return \Rcm\Entity\Language
     * @throws \Rcm\Exception\LanguageNotFoundException
     */
    public function getLanguage($language)
    {
        $entityMgr = $this->entityMgr;

        //Get Language

        $languageRepo = $entityMgr->getRepository(
            '\Rcm\Entity\Language'
        );

        if (strlen($language) === 3) {
            $languageEntity
                = $languageRepo->findOneBy(array("iso639_2t" => $language));

            if (empty($languageEntity)) {
                $languageEntity
                    = $languageRepo->findOneBy(array("iso639_2b" => $language));
            }
        } elseif (strlen($language) === 2) {
            $languageEntity
                = $languageRepo->findOneBy(array("iso639_1" => $language));
        }

        if (empty($languageEntity)) {
            throw new LanguageNotFoundException(
                'No language found in DB for: ' . $language
            );
        }

        return $languageEntity;
    }
}