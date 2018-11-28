<?php

namespace Rcm\ImmutableHistory;

interface HumanReadableVersionDescriber
{
    public function getResourceTypeDescription(VersionEntityInterface $version): string;

    public function getParentCurrentLocationDescription(VersionEntityInterface $version): string;

    public function getResourceLocationDescription(VersionEntityInterface $version): string;
}
