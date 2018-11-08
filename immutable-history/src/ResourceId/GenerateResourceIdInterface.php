<?php

namespace Rcm\ImmutableHistory\ResourceId;

interface GenerateResourceIdInterface
{
    public function __invoke(): string;
}
