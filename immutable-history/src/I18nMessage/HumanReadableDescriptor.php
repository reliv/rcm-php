<?php

namespace Rcm\ImmutableHistory\I18nMessage;

use Rcm\ImmutableHistory\HumanReadableVersionDescriber;
use Rcm\ImmutableHistory\VersionEntityInterface;

class HumanReadableDescriber implements HumanReadableVersionDescriber
{
    public function getResourceTypeDescription(VersionEntityInterface $version): string
    {
        return 'i18n_message';
    }

    public function getParentCurrentLocationDescription(VersionEntityInterface $version): string
    {
        return $version->getLocale();
    }

    public function getResourceLocationDescription(VersionEntityInterface $version): string
    {
        return $version->getDefaultText();
    }
}
