<?php

namespace Rcm\Block\Renderer;

use Phly\Mustache\Mustache;
use Phly\Mustache\Resolver\DefaultResolver;
use Rcm\Block\Config\Config;
use Rcm\Block\Config\ConfigRepository;
use Rcm\Block\InstanceWithData\InstanceWithData;

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

        $viewData = [
            'id' => $instance->getId(),
            'config' => $instance->getConfig(),
            'data' => $instance->getData(),
        ];

        $musacheEngine = new \Mustache_Engine([
                'helpers' => [
                    /**
                     * Allows the ussage of {{#jsonStringify}}config{{/jsonStringify}}
                     *
                     * In the future we may parse for dots to allow usage of something like
                     * {{#jsonStringify}}config.something.something{{/jsonStringify}}
                     */
                    'jsonStringify' => function ($value) use ($viewData) {
                        return json_encode($viewData[$value]);
                    }
                ]
            ]
        );

        return $musacheEngine->render(
            file_get_contents($blockConfig->getDirectory() . '/template.mustache'),
            $viewData
        );
    }
}
