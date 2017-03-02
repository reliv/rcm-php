<?php

namespace Rcm\Block;

use Rcm\Core\Repository\AbstractRepository;
use Rcm\Core\Repository\Repository;

/**
 * @GammaRelease
 * Class ConfigRepositoryBc
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ConfigRepositoryBc extends AbstractRepository implements Repository
{
    /**
     * @var ConfigProviderJson
     */
    protected $configProviderJson;

    /**
     * @var ConfigProviderOld
     */
    protected $configProviderOld;

    /**
     * Constructor.
     *
     * @param ConfigProviderJson $configProviderJson
     * @param ConfigProviderOld  $configProviderOld
     */
    public function __construct(
        ConfigProviderJson $configProviderJson,
        ConfigProviderOld $configProviderOld
    ) {
        $this->configProviderJson = $configProviderJson;
        $this->configProviderOld = $configProviderOld;
    }

    /**
     * __invoke
     *
     * @return array
     */
    public function __invoke()
    {
        $configsJson = $this->configProviderJson->__invoke();
        $configsOld = $this->configProviderOld->__invoke();

        return array_merge($configsOld, $configsJson);
    }
}
