<?php

namespace Rcm\ImmutableHistory;

interface VersionEntityInterface
{
    public function getId(): int;

    public function getResourceId(): string;

    public function getStatus(): string;

    public function getAction(): string;

    /**
     * @return array | null
     */
    public function getContentAsArray();
}
