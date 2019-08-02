<?php
//
//namespace Rcm\CurrentRequest;
//
//use Interop\Http\ServerMiddleware\DelegateInterface;
//use Interop\Http\ServerMiddleware\MiddlewareInterface;
//use Psr\Http\Message\ResponseInterface;
//use Psr\Http\Message\ServerRequestInterface;
//
///**
// * @deprecated user "current request context" instead.
// *
// * Register this middleware early in your pipeline to allow later code to use the GetCurrentRequest Service.
// *
// * Class CurrentRequestEarlyMiddleware
// * @package Rcm\CurrentRequest
// */
//class CurrentRequestEarlyMiddleware implements MiddlewareInterface
//{
//    protected $getCurrentRequest;
//
//    public function __construct(GetCurrentRequest $getCurrentRequest)
//    {
//        $this->getCurrentRequest = $getCurrentRequest;
//    }
//
//    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
//    {
//        $this->getCurrentRequest->setCurrentRequest($request);
//        return $delegate->process($request);
//    }
//}
