<?php

namespace RcmAdmin\View\Helper;

use Rcm\Block\Config\Config;
use Rcm\Block\Config\ConfigRepository;
use Rcm\Service\PluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * availablePluginsList
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\View\Helper
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AvailablePluginsJsList extends AbstractHelper
{
    /**
     * @var
     */
    protected $serviceLocator;

    /**
     * __invoke
     *
     * @return void
     */
    public function __invoke()
    {
        /** @var \Zend\View\Renderer\PhpRenderer $renderer */
        $view = $this->getView();
        $headScript = $view->headScript();

        /** @var PluginManager $plugins */
        $plugins = $this->getServiceLocator()
            ->get('Rcm\Service\PluginManager')
            ->listAvailablePluginsByType();

        $plugins['Site Wide'] = $this->getServiceLocator()
            ->get('Rcm\Service\CurrentSite')
            ->listAvailableSiteWidePlugins();

        foreach ($plugins['Site Wide'] as $key => $value) {
            if (empty($plugins['Site Wide'][$key]['icon'])) {
                $plugins['Site Wide'][$key]['icon'] = $this->getDefaultPluginIcon();
            }
        }

        // @GammaRelease
        /** @var ConfigRepository $blockConfigRepository */
        $blockConfigRepository = $this->getServiceLocator()
            ->get(ConfigRepository::class);

        $blockConfigs = $blockConfigRepository->find();

        $blockConfigArray = [];

        /**
         * @var Config $blockConfig
         */
        foreach ($blockConfigs as $blockConfig) {
            $blockConfigArray[$blockConfig->getName()] = $blockConfig->toArray();
        }

        $script = 'var rcmAvailablePlugins=' . json_encode($plugins) . ";\n\n";
        // @GammaRelease
        $script .= 'var rcmBlockConfigs=' . json_encode($blockConfigArray) . ";";

        $headScript->appendScript(
            $script
        );
    }

    /**
     * getDefaultPluginIcon
     *
     * @return string
     */
    public function getDefaultPluginIcon()
    {
        $config = $this->getServiceLocator()->get('Config');

        return $config['Rcm']['defaultPluginIcon'];
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator->getServiceLocator();
    }
}
