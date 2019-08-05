<?php

namespace Reliv\App\RcmApi\Site\PipeRat2\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Api\GetSiteByRequest;
use Rcm\Entity\Site;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeWhere;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeWhereMutator;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RequestAttributeWhereMutatorSite implements WithRequestAttributeWhereMutator
{
    protected $getSiteByRequest;

    /**
     * @param GetSiteByRequest $getSiteByRequest
     */
    public function __construct(
        GetSiteByRequest $getSiteByRequest
    ) {
        $this->getSiteByRequest = $getSiteByRequest;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $options
     *
     * @return ServerRequestInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $options = []
    ): ServerRequestInterface {
        $where = $request->getAttribute(
            WithRequestAttributeWhere::ATTRIBUTE,
            []
        );

        //$request->getAttribute can still return nulls when defaulted to [] so we need this to prevent exceptions
        if (!is_array($where)) {
            $where = [];
        }

        if (array_key_exists('current', $where)) {
            /** @var Site $currentSite */
            $currentSite = $this->getSiteByRequest->__invoke(
                $request
            );
            unset($where['current']);
            $where['siteId'] = $currentSite->getSiteId();
        }

        return $request->withAttribute(
            WithRequestAttributeWhere::ATTRIBUTE,
            $where
        );
    }
}
