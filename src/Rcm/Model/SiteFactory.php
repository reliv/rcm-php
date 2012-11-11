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
    public function getSite($domainName, $language)
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

        return $site;
    }

    /**
     * Creates a new site entity
     *
     * @param string   $domainName       domain name
     * @param Country  $country          country
     * @param Language $language         language
     * @param integer  $ownerAccountNum  owner account number
     * @param array   $additionalDomain an additional domain that redirects
     *                                   to this site
     *
     * @return Site
     */
    public function createNewSite(
        $domainName,
        $theme,
        \Rcm\Entity\Country $country,
        \Rcm\Entity\Language $language,
        $ownerAccountNum,
        $additionalDomain = array(),
        $initialSiteWidePlugins = array()
    ) {
        $entityMgr = $this->entityMgr;

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
                    $domain[$key+1] = new \Rcm\Entity\Domain();
                    $domain[$key+1]->setDomainName($additionalDomainName);
                    $domain[$key+1]->setPrimary($domain[0]);
                    $domain[0]->setAdditionalDomain($domain[$key+1]);
                    $entityMgr->persist($domain[$key+1]);
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

        if (empty($domainName)) {
            throw new \Rcm\Exception\InvalidArgumentException(
                'No Domain Found.'
            );
        }


        $query = array('domain' => $domainName);
        $entityMgr = $this->entityMgr;
        $repo = $entityMgr->getRepository('\Rcm\Entity\Domain');
        $domainEntity = $repo->findOneBy($query);

        if (empty($domainEntity)) {
            throw new \Rcm\Exception\RuntimeException(
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