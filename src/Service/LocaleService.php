<?php

namespace Rcm\Service;

/**
 * Class LocaleService
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class LocaleService
{
    /**
     * @var string|null
     */
    protected $defaultLocale = null;

    /**
     * LocaleService constructor.
     *
     * @param array $config
     */
    public function __construct(
        $config
    ) {
        $this->defaultLocale = $config;
    }

    /**
     * setDefaultDomainName
     *
     * @param array $config
     *
     * @return void
     */
    protected function setDefaultDomainName($config)
    {
        if (!empty($config['Rcm']['defaultLocale'])) {
            $this->defaultLocale = $config['Rcm']['defaultLocale'];
        }
    }

    /**
     * getDefaultLocale
     *
     * @return null|string
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * setLocale
     * NOTE: We do NOT set LC_ALL because it causes "n tilde"
     * chars to be not json encodable after they have been strtolower'd
     *
     * @param string|null $locale
     *
     * @return void
     */
    public function setLocale(
        $locale = null
    ) {
        if (empty($locale)) {
            $locale = $this->getDefaultLocale();
        }

        if (empty($locale)) {
            // @todo warning or error
            return;
        }

        /* Conversion for Ubuntu and Mac local settings. */
        if (!setlocale(LC_MONETARY, $locale . '.utf8')) {
            if (!setlocale(LC_MONETARY, $locale . '.UTF-8')) {
                setlocale(LC_MONETARY, 'en_US.UTF-8');
            }
        }

        /* Conversion for Ubuntu and Mac local settings. */
        if (!setlocale(LC_NUMERIC, $locale . '.utf8')) {
            if (!setlocale(LC_NUMERIC, $locale . '.UTF-8')) {
                setlocale(LC_NUMERIC, 'en_US.UTF-8');
            }
        }

        /* Conversion for Ubuntu and Mac local settings. */
        if (!setlocale(LC_TIME, $locale . '.utf8')) {
            if (!setlocale(LC_TIME, $locale . '.UTF-8')) {
                setlocale(LC_TIME, 'en_US.UTF-8');
            }
        }

        \Locale::setDefault($locale);
    }

    /**
     * getLocale
     *
     * @return string
     */
    public function getLocale()
    {
        return \Locale::getDefault();
    }
}
