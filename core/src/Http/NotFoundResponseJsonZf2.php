<?php

namespace Rcm\Http;

use Zend\Http\Response;

class NotFoundResponseJsonZf2 extends Response
{
    public function __construct()
    {
        $this->setStatusCode(404);
        $this->setContent(json_encode(['errorMessage' => 'Not found']));
    }
}
