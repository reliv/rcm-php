<?php

namespace RcmUser;

/**
 * ZF2 Module
 *
 * @author James Jervis - https://github.com/jerv13
 */
class Module
{
    /**
     * @return array
     */
    public function getConfig()
    {
        $moduleConfig = new ModuleConfig();

        $config = $moduleConfig->__invoke();

        $config['service_manager'] = $config['dependencies'];

        return $config;
    }
}
