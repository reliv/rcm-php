<?php
/**
 * PluginRenderApiController.php
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Rcm\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;


/**
 * PluginRenderApiController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class NewPluginInstanceApiController extends AbstractActionController
{
    public function getNewInstanceAction()
    {
        $routeMatch = $this->getEvent()->getRouteMatch();
        $pluginType = $routeMatch->getParam('type');
        $instanceId = $routeMatch->getParam('instanceId');
        $pluginManager = $this->getServiceLocator()
            ->get('Rcm\Service\PluginManager');
        if ($instanceId < 0) {
            $instanceConfig = $pluginManager
                ->getDefaultInstanceConfig($pluginType);
        } else {
            $instanceConfig = $pluginManager->getInstanceConfig($instanceId);
        }
        $viewData = $pluginManager->getPluginViewData(
            $pluginType,
            $instanceId,
            $instanceConfig
        );
        $jsonModel = new JsonModel();
        $jsonModel->setVariables(
            array(
                'display' => $viewData['html'],
                'js' => '', //$viewData['js'],
                'css' => '', //$viewData['css']
            )
        );
        return $jsonModel;
    }
} 