<?php

namespace Rcm\ImmutableHistory;

interface ContentInterface
{
    public function toArrayForLongTermStorage(): array;
}
