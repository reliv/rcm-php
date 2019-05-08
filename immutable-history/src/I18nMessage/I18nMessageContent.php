<?php

namespace Rcm\ImmutableHistory\I18nMessage;

use Rcm\ImmutableHistory\ContentInterface;

class I18nMessageContent implements ContentInterface
{
    const CONTENT_SCHEMA_VERSION = 1;

    protected $text;

    public function __construct(
        string $text
    ) {
        $this->text = $text;
    }

    public function toArrayForLongTermStorage(): array
    {
        return [
            'text' => $this->text,
            'contentSchemaVersion' => self::CONTENT_SCHEMA_VERSION,
        ];
    }
}
