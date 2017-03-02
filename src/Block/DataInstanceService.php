<?php

namespace Rcm\Block;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @GammaRelease
 * Class DataInstanceService
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DataInstanceService
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
     * @param DataService        $dataService
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
     * @param                        BlockInstance
     * @param ServerRequestInterface $request
     *
     * @return Data
     */
    public function __invoke($instanceId, ServerRequestInterface $request)
    {
        $instance = $this->instanceRepository->findById($instanceId);

        if (empty($instance)) {
            return null;
        }

        $data = $this->dataService->__invoke($instance, $request);

        $dataInstance = new DataBasic(
            $instance->getId(),
            $instance->getConfig(),
            $data
        );

        return $dataInstance;
    }
}
