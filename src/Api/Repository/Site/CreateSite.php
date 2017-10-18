<?php

namespace Rcm\Api\Repository\Site;

use Aws\Swf\Exception\DomainAlreadyExistsException;
use Doctrine\ORM\EntityManager;
use Rcm\Api\Repository\Country\FindCountryByIso3;
use Rcm\Api\Repository\Domain\FindDomainByName;
use Rcm\Api\Repository\Language\FindLanguageByIso6392t;
use Rcm\Api\Repository\Options;
use Rcm\Entity\Country;
use Rcm\Entity\Domain;
use Rcm\Entity\Site;
use Rcm\Exception\CountryNotFoundException;
use Rcm\Exception\LanguageNotFoundException;
use Rcm\Exception\PropertyMissing;
use Rcm\Tracking\Model\Tracking;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CreateSite
{
    const PROPERTY_HOST = 'host';
    // Theme name: 'GuestResponsive'
    const PROPERTY_THEME_NAME = 'theme';
    const PROPERTY_LAYOUT = 'layout';
    const PROPERTY_STATUS = 'status';
    /* possibly required */
    // ISO3 Country code: 'USA'
    const PROPERTY_COUNTRY_ISO3 = 'countryIso3';
    // ISO 639-2/T Language Code: 'eng'
    const PROPERTY_LANGUAGE_ISO_939_2T = 'languageIso9392t';
    // Site title
    const PROPERTY_TITLE = 'title';
    /* other */
    // Path to favicon: '/images/favicon.ico'
    const PROPERTY_FAVICON = 'favicon';
    // Login page path: '/login'
    const PROPERTY_LOGIN_PAGE = 'loginPage';
    const PROPERTY_NOT_AUTHORIZED_PAGE = 'notAuthorizedPage';
    const PROPERTY_NOT_FOUND_PAGE = 'notFoundPage';

    const DEFAULT_STATUS = Site::STATUS_ACTIVE;
    const DEFAULT_FAVICON = '/images/favicon.ico';
    const DEFAULT_LOGIN_PAGE = '/login';
    const DEFAULT_NOT_AUTHORIZED_PAGE = '/not-authorized';
    const DEFAULT_NOT_FOUND_PAGE = 'not-found';

    protected $entityManager;
    protected $findDomainByName;
    protected $findCountryByIso3;
    protected $findLanguageByIso6392t;

    /**
     * @param EntityManager          $entityManager
     * @param FindDomainByName       $findDomainByName
     * @param FindCountryByIso3      $findCountryByIso3
     * @param FindLanguageByIso6392t $findLanguageByIso6392t
     */
    public function __construct(
        EntityManager $entityManager,
        FindDomainByName $findDomainByName,
        FindCountryByIso3 $findCountryByIso3,
        FindLanguageByIso6392t $findLanguageByIso6392t
    ) {
        $this->entityManager = $entityManager;
        $this->findDomainByName = $findDomainByName;
        $this->findCountryByIso3 = $findCountryByIso3;
        $this->findLanguageByIso6392t = $findLanguageByIso6392t;
    }

    /**
     * @param array  $properties
     * @param string $createdByUserId
     * @param string $createdReason
     * @param array  $options
     *
     * @return Site
     */
    public function __invoke(
        array $properties = [],
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON,
        array $options = []
    ): Site {
        $this->assertValidProperties($properties);

        $domain = $this->buildDomain(
            Options::get(
                $properties,
                self::PROPERTY_HOST
            ),
            $createdByUserId,
            $createdReason
        );

        $language = $this->buildLanguage(
            Options::get(
                $properties,
                self::PROPERTY_LANGUAGE_ISO_939_2T
            )
        );

        $country = $this->buildCountry(
            Options::get(
                $properties,
                self::PROPERTY_COUNTRY_ISO3
            )
        );

        $newSite = new Site(
            $createdByUserId,
            'Create new site in ' . get_class($this)
            . ' for: ' . $createdReason
        );

        $newSite->setDomain(
            $domain
        );

        $newSite->setLanguage(
            $language
        );

        $newSite->setCountry(
            $country
        );

        $newSite->setSiteLayout(
            Options::get(
                $properties,
                self::PROPERTY_LAYOUT
            )
        );

        $newSite->setSiteTitle(
            Options::get(
                $properties,
                self::PROPERTY_TITLE
            )
        );

        $newSite->setStatus(
            Options::get(
                $properties,
                self::PROPERTY_STATUS,
                self::DEFAULT_STATUS
            )
        );

        $newSite->setFavIcon(
            Options::get(
                $properties,
                self::PROPERTY_FAVICON,
                self::DEFAULT_FAVICON
            )
        );

        $newSite->setLoginPage(
            Options::get(
                $properties,
                self::PROPERTY_LOGIN_PAGE,
                self::DEFAULT_LOGIN_PAGE
            )
        );

        $newSite->setNotAuthorizedPage(
            Options::get(
                $properties,
                self::PROPERTY_NOT_AUTHORIZED_PAGE,
                self::DEFAULT_NOT_AUTHORIZED_PAGE
            )
        );

        $newSite->setNotFoundPage(
            Options::get(
                $properties,
                self::PROPERTY_NOT_FOUND_PAGE,
                self::DEFAULT_NOT_FOUND_PAGE
            )
        );

        $domain->setSite($newSite);

        $this->entityManager->persist($newSite);
        $this->entityManager->persist($domain);

        $this->entityManager->flush($newSite);
        $this->entityManager->flush($domain);

        return $newSite;
    }

    /**
     * @param string $domainName
     * @param string $createdByUserId
     * @param string $createdReason
     * @param array  $properties
     *
     * @return Domain|\Rcm\Entity\Domain[]
     */
    protected function buildDomain(
        string $domainName,
        string $createdByUserId,
        string $createdReason,
        array $properties = []
    ) {
        $domain = $this->findDomainByName->__invoke(
            $domainName
        );

        if (!empty($domain)) {
            throw new DomainAlreadyExistsException(
                "Domain {$domainName} was found and should not be duplicated."
            );
        }

        $domain = new Domain(
            $createdByUserId,
            'Create new domain in ' . get_class($this)
            . ' for: ' . $createdReason
        );

        $domain->setDomainName(
            $domainName
        );

        // @todo Get any other properties and set them

        return $domain;
    }

    /**
     * @param string $countryIso3
     *
     * @return null|Country
     */
    protected function buildCountry(
        string $countryIso3
    ) {
        $country = $this->findCountryByIso3->__invoke(
            $countryIso3
        );

        if (empty($country)) {
            throw new CountryNotFoundException(
                "Country {$countryIso3} could not be found."
            );
        }

        return $country;
    }

    /**
     * @param string $languageIso6392t
     *
     * @return null|\Rcm\Entity\Language
     */
    protected function buildLanguage(
        string $languageIso6392t
    ) {
        $language = $this->findLanguageByIso6392t->__invoke(
            $languageIso6392t
        );

        if (empty($language)) {
            throw new LanguageNotFoundException(
                "Language {$language} could not be found."
            );
        }

        return $language;
    }

    /**
     * @param array $properties
     *
     * @return void
     * @throws \Exception
     */
    protected function assertValidProperties(array $properties)
    {
        $domainName = Options::get(
            $properties,
            self::PROPERTY_HOST
        );

        if (empty($domainName)) {
            throw new PropertyMissing('Host (domain name) is required to create site');
        }

        $languageIso9392t = Options::get(
            $properties,
            self::PROPERTY_LANGUAGE_ISO_939_2T
        );

        if (empty($languageIso9392t)) {
            throw new PropertyMissing('LanguageIso9392t code is required to create site');
        }

        $countryIso3 = Options::get(
            $properties,
            self::PROPERTY_COUNTRY_ISO3
        );

        if (empty($countryIso3)) {
            throw new PropertyMissing('CountryIso3 code is required to create site');
        }

        $siteLayout = Options::get(
            $properties,
            self::PROPERTY_LAYOUT
        );

        if (empty($siteLayout)) {
            throw new PropertyMissing('Layout is required to create site');
        }

        $title = Options::get(
            $properties,
            self::PROPERTY_TITLE
        );

        if (empty($title)) {
            throw new PropertyMissing('Title is required to create site');
        }
    }
}
