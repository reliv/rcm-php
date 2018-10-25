<?php

namespace RcmAdmin\Service;

use Rcm\Block\Config\Config;
use Rcm\Block\Config\ConfigRepository;

/**
 * Class RenderAvailableBlocksJs
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RendererAvailableBlocksJs
{
    /**
     * @var ConfigRepository
     */
    protected $blockConfigRepository;

    /**
     * Constructor.
     *
     * @param ConfigRepository $blockConfigRepository
     */
    public function __construct(
        ConfigRepository $blockConfigRepository
    ) {
        $this->blockConfigRepository = $blockConfigRepository;
    }

    /**
     * render
     * __invoke
     *
     * @return string
     */
    public function __invoke()
    {
        // @GammaRelease
        $blockConfigs = $this->blockConfigRepository->find();

        $blockConfigArray = [];

        /**
         * @var Config $blockConfig
         */
        foreach ($blockConfigs as $blockConfig) {
            $blockConfigArray[$blockConfig->getName()] = $blockConfig->toArray();
        }

        return 'var rcmBlockConfigs=' . json_encode($blockConfigArray) . ";";
    }
}
