<?php

namespace Rcm\Block\Renderer;

use Rcm\Block\InstanceWithData\InstanceWithData;

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
     * @var RendererRepository
     */
    protected $rendererProviderRepository;

    /**
     * Constructor.
     *
     * @param RendererRepository $rendererProviderRepository
     */
    public function __construct(
        RendererRepository $rendererProviderRepository
    ) {
        $this->rendererProviderRepository = $rendererProviderRepository;
    }

    /**
     * __invoke
     *
     * @param InstanceWithData $instance
     *
     * @return string Rendered Html
     */
    public function __invoke(InstanceWithData $instance)
    {
        $renderer = $this->rendererProviderRepository->findByName($instance->getName());

        return $renderer->__invoke($instance);
    }
}
