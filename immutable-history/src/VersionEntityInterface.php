<?php

namespace Rcm\ImmutableHistory;

interface VersionEntityInterface
{
    public function getResourceId(): string;

    public function getStatus(): string;
}
