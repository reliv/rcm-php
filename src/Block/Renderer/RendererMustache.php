<?php

namespace Rcm\Block\Renderer;

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
                     * And for depth, you can do things like
                     * {{#jsonStringify}}config.backgroundImage{{/jsonStringify}}
                     */
                    'jsonStringify' => function ($value) use ($viewData) {
                        return json_encode($this->getFromArrayByDotDelimitedPath($value, $viewData));
                    }
                ]
            ]
        );

        return $musacheEngine->render(
            file_get_contents($blockConfig->getDirectory() . '/template.mustache'),
            $viewData
        );
    }

    /**
     * Returns the value from an array by the given dot-seperated path
     *
     * @param string $path a dot delimited string
     * @param array $array
     * @return array|mixed
     */
    protected function getFromArrayByDotDelimitedPath(string $path, array $array)
    {
        $value = $array;
        $i = 0;
        foreach (explode('.', $path) as $key) {
            $i++;
            $value = $value[$key];
        }

        return $value;
    }
}
