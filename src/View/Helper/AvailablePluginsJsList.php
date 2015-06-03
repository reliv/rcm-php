<?php
/**
 * availablePluginsList.php
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\View\Helper
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmAdmin\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * availablePluginsList
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\View\Helper
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AvailablePluginsJsList extends AbstractHelper implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function __invoke()
    {
        /** @var \Zend\View\Renderer\PhpRenderer $renderer */
        $view = $this->getView();
        $headScript = $view->headScript();

        $plugins = $this->getServiceLocator()
            ->get('Rcm\Service\PluginManager')
            ->listAvailablePluginsByType();

        $plugins['Site Wide'] = $this->getServiceLocator()
            ->get('Rcm\Service\CurrentSite')
            ->listAvailableSiteWidePlugins();

        $headScript->appendScript(
            'var rcmAvailablePlugins=' . json_encode($plugins)
        );
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
