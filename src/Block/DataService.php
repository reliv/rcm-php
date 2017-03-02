<?php

namespace Rcm\Block;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @GammaRelease
 * Class DataService
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DataService implements DataProvider
{
    /**
     * @var DataProviderRepository
     */
    protected $providerRepository;

    /**
     * Constructor.
     *
     * @param DataProviderRepository $providerRepository
     */
    public function __construct(DataProviderRepository $providerRepository)
    {
        $this->providerRepository = $providerRepository;
    }

    /**
     * Plugins automatically get their instance configs when rendering. A data service like this
     * is used to return any custom data in addition to the instance config to the plugin
     * template for rendering.
     *
     * @param Instance               $instance
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function __invoke(Instance $instance, ServerRequestInterface $request) : array
    {
        $provider = $this->providerRepository->findById($instance->getName());

        return $provider->__invoke($instance, $request);
    }
}
