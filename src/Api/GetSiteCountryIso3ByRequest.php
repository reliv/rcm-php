<?php

namespace Rcm\Api;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetSiteCountryIso3ByRequest
{
    protected $getSiteByRequest;

    public function __construct(
        GetSiteByRequest $getSiteByRequest
    ) {
        $this->getSiteByRequest = $getSiteByRequest;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return string|null
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ) {
        $site = $this->getSiteByRequest->__invoke(
            $request,
            $options
        );

        if (empty($site)) {
            return null;
        }

        $country = $site->getCountry();

        if (empty($country)) {
            return null;
        }

        return $country->getIso3();
    }
}
