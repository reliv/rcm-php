<?php

namespace Rcm\Exception;

class InputFilterFoundInvalidDataException extends \RuntimeException
{
    protected $messages;
    protected $mainMessage;

    public function __construct(string $mainMessage, array $messages)
    {
        $this->mainMessage = $mainMessage;
        $this->messages = $messages;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return mixed
     */
    public function getMainMessage(): string
    {
        return $this->mainMessage;
    }
}
