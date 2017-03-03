<?php

namespace Rcm\Block\InstanceWithData;

use Psr\Http\Message\ServerRequestInterface;
use Rcm\Block\DataProvider\DataService;
use Rcm\Block\Instance\Instance;
use Rcm\Block\Instance\InstanceRepository;

/**
 * @GammaRelease
 * Class InstanceWithDataService
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class InstanceWithDataService
{
    /**
     * @var InstanceRepository
     */
    protected $instanceRepository;

    /**
     * @var DataService
     */
    protected $dataService;

    /**
     * Constructor.
     *
     * @param InstanceRepository $instanceRepository
     * @param DataService $dataService
     */
    public function __construct(
        InstanceRepository $instanceRepository,
        DataService $dataService
    ) {
        $this->instanceRepository = $instanceRepository;
        $this->dataService = $dataService;
    }

    /**
     * Plugins automatically get their instance configs when rendering. A data service like this
     * is used to return any custom data in addition to the instance config to the plugin
     * template for rendering.
     *
     * @param string $instanceId
     * @param ServerRequestInterface $request
     *
     * @return null|DataBasic
     */
    public function __invoke($instanceId, ServerRequestInterface $request)
    {
        /** @var Instance $instance */
        $instance = $this->instanceRepository->findById($instanceId);

        if (empty($instance)) {
            return null;
        }

        $data = $this->dataService->__invoke($instance, $request);

        $dataInstance = new InstanceWithDataBasic(
            $instance->getId(),
            $instance->getName(),
            $instance->getConfig(),
            $data
        );

        return $dataInstance;
    }
}
