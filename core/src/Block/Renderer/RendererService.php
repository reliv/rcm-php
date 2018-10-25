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
    protected $rendererRepository;

    /**
     * Constructor.
     *
     * @param RendererRepository $rendererRepository
     */
    public function __construct(
        RendererRepository $rendererRepository
    ) {
        $this->rendererRepository = $rendererRepository;
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
        $renderer = $this->rendererRepository->findByName($instance->getName());

        if (!$renderer) {
            throw new \Exception('No renderer found for block ' . $instance->getName());
        }

        return $renderer->__invoke($instance);
    }
}
