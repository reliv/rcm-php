<?php

namespace Rcm\ImmutableHistory\I18nMessage;

use Rcm\ImmutableHistory\LocatorInterface;

class I18nMessageLocator implements LocatorInterface
{
    protected $locale;
    protected $defaultText;

    /**
     * RedirectLocator constructor.
     * @param string $locale
     * @param string $default
     */
    public function __construct(string $locale, $defaultText)
    {
        $this->locale = $locale;
        $this->defaultText = $defaultText;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getDefaultText()
    {
        return $this->defaultText;
    }

    public function toArray(): array
    {
        return [
            'defaultText' => $this->defaultText,
            'locale' => $this->locale,
        ];
    }
}
