<?php
namespace Reliv\App\RcmApi;

/**
 * ZF Module file
 *
 * Class Module
 * @package RcmsApi
 */
class Module
{
    /**
     * getAutoloaderConfig() is a requirement for all Modules in ZF2.  This
     * function is included as part of that standard.  See Docs on ZF2 for more
     * information.
     *
     * @return array Returns array to be used by the ZF2 Module Manager
     */
    public function getConfig()
    {
        $moduleConfig = new ModuleConfig();
        $config = $moduleConfig->__invoke();
        $config['service_manager'] = $config['dependencies'];
        unset($config['dependencies']);
        return $config;
    }
}
