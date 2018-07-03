<?php

namespace Rcm\Block\Renderer;

use Phly\Mustache\Mustache;
use Phly\Mustache\Resolver\DefaultResolver;
use Rcm\Block\Config\Config;
use Rcm\Block\Config\ConfigRepository;
use Rcm\Block\InstanceWithData\InstanceWithData;
use Reliv\WhiteRat\Whitelist;

class RendererClientReact implements Renderer
{
    protected $blockConfigRepository;

    public function __construct(ConfigRepository $blockConfigRepository)
    {
        $this->blockConfigRepository = $blockConfigRepository;
    }

    /**
     * __invoke
     *
     * @param InstanceWithData $instance
     *
     * @return string
     */
    public function __invoke(InstanceWithData $instance)
    {
        /**
         * @var $blockConfig Config
         */
        $blockConfig = $this->blockConfigRepository->findById($instance->getName());

        $resolver = new DefaultResolver();
        $resolver->addTemplatePath($blockConfig->getDirectory());

        $mustache = new Mustache();
        $mustache->getResolver()->attach($resolver);

        $configJsonWhitelist = new Whitelist($blockConfig->getConfigJsonWhitelist());

        $clientBlockData = [
            'id' => $instance->getId(),
            'data' => $instance->getData(),
            'config' => $configJsonWhitelist->filter($instance->getConfig()),
        ];

        $reactAppDivId = 'renderClientReactBlock' . $instance->getId();

        return '<div id="' . $reactAppDivId . '"></div>'
            . '<script>'
            . 'clientReactBlockRenderer.renderBlock('
            . 'document.getElementById(' . json_encode($reactAppDivId) . '),'
            . json_encode($instance->getName()) . ','
            . json_encode($clientBlockData)
            . ');' .
            '</script>';
    }
}
