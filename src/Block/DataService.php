<?php

namespace Rcm\Block;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class DataService
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DataService implements Provider
{
    /**
     * @var ProviderRepository
     */
    protected $providerRepository;

    /**
     * Constructor.
     *
     * @param ProviderRepository $providerRepository
     */
    public function __construct(ProviderRepository $providerRepository)
    {
        $this->providerRepository = $providerRepository;
    }

    /**
     * Plugins automatically get their instance configs when rendering. A data service like this
     * is used to return any custom data in addition to the instance config to the plugin
     * template for rendering.
     *
     * @param Instance $instance
     * @param ServerRequestInterface $request
     * @return array
     */
    public function __invoke(Instance $instance, ServerRequestInterface $request) : array
    {
        $provider = $this->providerRepository->findById($instance->getName());

        return $provider->__invoke($instance, $request);
    }
}
