<?php

namespace Rcm\Block;

/**
 * @GammaRelease
 * Class RendererService
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RendererService implements Renderer
{
    /**
     * @var RendererProviderRepository
     */
    protected $rendererProviderRepository;

    /**
     * Constructor.
     *
     * @param RendererProviderRepository $rendererProviderRepository
     */
    public function __construct(
        RendererProviderRepository $rendererProviderRepository
    ) {
        $this->rendererProviderRepository = $rendererProviderRepository;
    }

    /**
     * __invoke
     *
     * @param Data $instance
     *
     * @return string Rendered Html
     */
    public function __invoke(Data $instance)
    {
        $renderer = $this->rendererProviderRepository->findById($instance->getName());

        return $renderer->__invoke($instance);
    }
}
