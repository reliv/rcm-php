<?php

namespace Rcm\Block\Renderer;

use Phly\Mustache\Mustache;
use Phly\Mustache\Resolver\DefaultResolver;
use Rcm\Block\Config\Config;
use Rcm\Block\Config\ConfigRepository;
use Rcm\Block\InstanceWithData\InstanceWithData;
use Reliv\WhiteRat\Whitelist;

class RendererMustache implements Renderer
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

        $viewData = [
            'id' => $instance->getId(),
            'config' => $instance->getConfig(),
            'data' => $instance->getData(),
            'configJson' => json_encode($configJsonWhitelist->__invoke($instance->getConfig())),
        ];

        return $mustache->render('template', $viewData);
    }
}
