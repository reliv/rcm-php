<?php

namespace Rcm\ImmutableHistory\Redirect;

use Rcm\ImmutableHistory\ContentInterface;

class RedirectContent implements ContentInterface
{
    const CONTENT_SCHEMA_VERSION = 1;

    protected $redirectUrl;
    protected $siteId;

    public function __construct(
        string $redirectUrl
    ) {
        $this->redirectUrl = $redirectUrl;
    }

    public function toArrayForLongTermStorage(): array
    {
        return [
            'redirectUrl' => $this->redirectUrl,
            'contentSchemaVersion' => self::CONTENT_SCHEMA_VERSION,
        ];
    }
}
