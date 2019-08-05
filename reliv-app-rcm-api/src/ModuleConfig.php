<?php

namespace Reliv\App\RcmApi;

use Zend\ConfigAggregator\ConfigAggregator;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfig
{
    public function __invoke()
    {
        $configManager = new ConfigAggregator(
            [
                new ModuleConfigPipeRat2Country(),
                new ModuleConfigPipeRat2Page(),
                new ModuleConfigPipeRat2Site(),
            ]
        );

        return $configManager->getMergedConfig();
    }
}
