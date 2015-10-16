<?php
/**
 * CMS Route
 *
 * This route will match CMS Urls to the DB pages in the CMS
 *
 * PHP version 5.5
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <shafer_w2002@yahoo.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Rcm\Route;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Zend\Http\Request;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\RequestInterface;

/**
 * CMS Route
 *
 * This route will match CMS Urls to the DB pages in the CMS
 *
 * PHP version 5.5
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <shafer_w2002@yahoo.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class Cms implements RcmRouteInterface, ServiceLocatorAwareInterface
{
    protected $controller = 'Rcm\Controller\CmsController';
    protected $action = 'index';

    protected $pageRepo;
    protected $assembledParams = array();
    protected $routePluginManager = null;
    protected $options = array();

    public function __construct($options)
    {
        $this->options = $options;
    }


    public function match(RequestInterface $request, $pathOffset = 0)
    {
        if (!$request instanceof Request) {
            return null;
        }

        $pageUrl = substr($request->getUri()->getPath(), $pathOffset);

        $type = $this->getPageType();

        $pageParams = $this->parseUrl($pageUrl);

        if (empty($pageParams)) {
            return null;
        }

        try {
            $page = $this->getPage($pageParams['pageName'], $type);
        } catch (\Exception $e) {
            return null;
        }

        if (empty($page)) {
            return false;
        }

        $cms_params = array(
            'page' => $page,
            'controller' => $this->getController($type),
            'action' => $this->getAction($type),
            'revision' => $pageParams['revision']
        );

        return new RouteMatch($cms_params, strlen($pageUrl));
    }

    protected function parseUrl($url)
    {
        $parsed = array(
            'pageName' => 'index',
            'revision' => null
        );

        $options = $this->getOptions();
        $path = substr($url, strlen($options['route']));

        $path = ltrim($path, '/');
        $splitPath = explode('/', $path);

        if (empty($splitPath[0])) {
            return $parsed;
        }

        $pageName = $splitPath[0];
        $revision = null;

        if (!empty($splitPath[1])) {
            $revision = $splitPath[1];
        }

        return array(
            'pageName' => $pageName,
            'revision' => $revision
        );
    }

    protected function getController($type)
    {
        $options = $this->getOptions();

        if (!empty($options['defaults']['controller'])) {
            return $options['defaults']['controller'];
        }

        $config = $this->getServiceLocator()->get('config');

        if (!empty($config['rcm']['pageTypes'][$type]['controller'])) {
            return $config['rcm']['pageTypes'][$type]['controller'];
        }

        return $this->controller;
    }

    /**
     * @param $pageName
     * @param $type
     * @return null|\Rcm\Entity\Page
     */
    protected function getPage($pageName, $type)
    {
        $serviceLocator = $this->getServiceLocator();

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $serviceLocator->get('Rcm\Service\CurrentSite');

        if (!$currentSite->getSiteId()) {
            return null;
        }

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository('\Rcm\Entity\Page');

        /* Get the Page for display */
        return $pageRepo->getPageByName(
            $currentSite,
            $pageName,
            $type
        );
    }

    protected function getAction($type)
    {
        $options = $this->getOptions();

        if (!empty($options['defaults']['action'])) {
            return $options['defaults']['action'];
        }

        $config = $this->getServiceLocator()->get('config');

        if (!empty($config['rcm']['pageTypes'][$type]['action'])) {
            return $config['rcm']['pageTypes'][$type]['action'];
        }

        return $this->action;
    }

    protected function getPageType()
    {
        $options = $this->getOptions();

        if (!empty($options['type'])) {
            return $options['type'];
        } else {
            return 'n';
        }
    }

    public function assemble(array $params = array(), array $options = array())
    {
        $options = $this->getOptions();

        $path = $options['route'];

        if (!empty($params['page'])) {
            $path = rtrim($path, '/').'/'.$params['page'];
        }

        if (!empty($params['revision'])) {
            $path = rtrim($path, '/').'/'.$params['revision'];
        }

        return $path;
    }

    public function getAssembledParams()
    {
        return $this->assembledParams;
    }

    /**
     * @param array $options
     * @return static
     */
    public static function factory($options = array())
    {
        if ($options instanceof \Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new InvalidArgumentException(__METHOD__ . ' expects an array or Traversable set of options');
        }

        if (!isset($options['route'])) {
            throw new \Rcm\Exception\InvalidArgumentException('Missing "route" in options array');
        }

        if (!isset($options['defaults'])) {
            $options['defaults'] = array();
        }

        return new static($options);
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $routePluginManager
     */
    public function setServiceLocator(ServiceLocatorInterface $routePluginManager)
    {
        $this->routePluginManager = $routePluginManager;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->routePluginManager->getServiceLocator();
    }

    protected function getOptions()
    {
        return $this->options;
    }
}
