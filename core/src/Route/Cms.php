<?php
//
//namespace Rcm\Route;
//
//use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
//use Rcm\Page\PageTypes\PageTypes;
//use Zend\Http\Request;
//use Zend\Mvc\Router\Http\RouteMatch;
//use Zend\ServiceManager\ServiceLocatorAwareInterface;
//use Zend\ServiceManager\ServiceLocatorInterface;
//use Zend\Stdlib\ArrayUtils;
//use Zend\Stdlib\RequestInterface;
//
///**
// * CMS Route
// *
// * This route will match CMS Urls to the DB pages in the CMS
// *
// * PHP version 5
// *
// * @category  Reliv
// * @package   Rcm
// * @author    Westin Shafer <shafer_w2002@yahoo.com>
// * @copyright 2015 Reliv International
// * @license   License.txt New BSD License
// * @version   Release: <package_version>
// * @link      https://github.com/reliv
// */
//
//class Cms implements RcmRouteInterface, ServiceLocatorAwareInterface
//{
//    /**
//     * @var string
//     */
//    protected $controller = 'Rcm\Controller\CmsController';
//    /**
//     * @var string
//     */
//    protected $action = 'index';
//    /**
//     * @var
//     */
//    protected $pageRepo;
//    /**
//     * @var array
//     */
//    protected $assembledParams = array();
//    /**
//     * @var null
//     */
//    protected $routePluginManager = null;
//    /**
//     * @var array
//     */
//    protected $options = array();
//
//    /**
//     * Cms constructor.
//     *
//     * @param $options
//     */
//    public function __construct($options)
//    {
//        $this->options = $options;
//    }
//
//    /**
//     * match
//     *
//     * @param RequestInterface $request
//     * @param int              $pathOffset
//     *
//     * @return bool|null|RouteMatch
//     */
//    public function match(RequestInterface $request, $pathOffset = 0)
//    {
//        if (!$request instanceof Request) {
//            return null;
//        }
//
//        $pageUrl = substr($request->getUri()->getPath(), $pathOffset);
//
//        $type = $this->getPageType();
//
//        $pageParams = $this->parseUrl($pageUrl);
//
//        if (empty($pageParams)) {
//            return null;
//        }
//
//        try {
//            $page = $this->getPage($pageParams['pageName'], $type);
//        } catch (\Exception $e) {
//            return null;
//        }
//
//        if (empty($page)) {
//            return false;
//        }
//
//        $cms_params = array(
//            'page' => $page,
//            'controller' => $this->getController($type),
//            'action' => $this->getAction($type),
//            'revision' => $pageParams['revision']
//        );
//
//        return new RouteMatch($cms_params, strlen($pageUrl));
//    }
//
//    /**
//     * parseUrl
//     *
//     * @param $url
//     *
//     * @return array
//     */
//    protected function parseUrl($url)
//    {
//        $parsed = array(
//            'pageName' => 'index',
//            'revision' => null
//        );
//
//        $options = $this->getOptions();
//        $path = substr($url, strlen($options['route']));
//
//        $path = ltrim($path, '/');
//        $splitPath = explode('/', $path);
//
//        if (empty($splitPath[0])) {
//            return $parsed;
//        }
//
//        $pageName = $splitPath[0];
//        $revision = null;
//
//        if (!empty($splitPath[1])) {
//            $revision = $splitPath[1];
//        }
//
//        return array(
//            'pageName' => $pageName,
//            'revision' => $revision
//        );
//    }
//
//    /**
//     * getController
//     *
//     * @param $type
//     *
//     * @return string
//     */
//    protected function getController($type)
//    {
//        $options = $this->getOptions();
//
//        if (!empty($options['defaults']['controller'])) {
//            return $options['defaults']['controller'];
//        }
//
//        $config = $this->getServiceLocator()->get('Config');
//
//        if (!empty($config['rcm']['pageTypes'][$type]['controller'])) {
//            return $config['rcm']['pageTypes'][$type]['controller'];
//        }
//
//        return $this->controller;
//    }
//
//    /**
//     * @param $pageName
//     * @param $type
//     * @return null|\Rcm\Entity\Page
//     */
//    protected function getPage($pageName, $type)
//    {
//        $serviceLocator = $this->getServiceLocator();
//
//        /** @var \Rcm\Entity\Site $currentSite */
//        $currentSite = $serviceLocator->get(\Rcm\Service\CurrentSite::class);
//
//        if (!$currentSite->getSiteId()) {
//            return null;
//        }
//
//        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
//        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
//
//        /** @var \Rcm\Repository\Page $pageRepo */
//        $pageRepo = $entityManager->getRepository(\Rcm\Entity\Page::class);
//
//        /* Get the Page for display */
//        return $pageRepo->getPageByName(
//            $currentSite,
//            $pageName,
//            $type
//        );
//    }
//
//    /**
//     * getAction
//     *
//     * @param $type
//     *
//     * @return string
//     */
//    protected function getAction($type)
//    {
//        $options = $this->getOptions();
//
//        if (!empty($options['defaults']['action'])) {
//            return $options['defaults']['action'];
//        }
//
//        $config = $this->getServiceLocator()->get('Config');
//
//        if (!empty($config['rcm']['pageTypes'][$type]['action'])) {
//            return $config['rcm']['pageTypes'][$type]['action'];
//        }
//
//        return $this->action;
//    }
//
//    /**
//     * getPageType
//     *
//     * @return string
//     */
//    protected function getPageType()
//    {
//        $options = $this->getOptions();
//
//        if (!empty($options['type'])) {
//            return $options['type'];
//        } else {
//            return PageTypes::NORMAL;
//        }
//    }
//
//    /**
//     * assemble
//     *
//     * @param array $params
//     * @param array $options
//     *
//     * @return string
//     */
//    public function assemble(array $params = array(), array $options = array())
//    {
//        $options = $this->getOptions();
//
//        $path = $options['route'];
//
//        if (!empty($params['page'])) {
//            $path = rtrim($path, '/').'/'.$params['page'];
//        }
//
//        if (!empty($params['revision'])) {
//            $path = rtrim($path, '/').'/'.$params['revision'];
//        }
//
//        return $path;
//    }
//
//    /**
//     * getAssembledParams
//     *
//     * @return array
//     */
//    public function getAssembledParams()
//    {
//        return $this->assembledParams;
//    }
//
//    /**
//     * @param array $options
//     * @return static
//     */
//    public static function factory($options = array())
//    {
//        if ($options instanceof \Traversable) {
//            $options = ArrayUtils::iteratorToArray($options);
//        } elseif (!is_array($options)) {
//            throw new InvalidArgumentException(__METHOD__ . ' expects an array or Traversable set of options');
//        }
//
//        if (!isset($options['route'])) {
//            throw new \Rcm\Exception\InvalidArgumentException('Missing "route" in options array');
//        }
//
//        if (!isset($options['defaults'])) {
//            $options['defaults'] = array();
//        }
//
//        return new static($options);
//    }
//
//    /**
//     * Set service locator
//     *
//     * @param ServiceLocatorInterface $routePluginManager
//     */
//    public function setServiceLocator(ServiceLocatorInterface $routePluginManager)
//    {
//        $this->routePluginManager = $routePluginManager;
//    }
//
//    /**
//     * Get service locator
//     *
//     * @return ServiceLocatorInterface
//     */
//    public function getServiceLocator()
//    {
//        return $this->routePluginManager->getServiceLocator();
//    }
//
//    /**
//     * getOptions
//     *
//     * @return array
//     */
//    protected function getOptions()
//    {
//        return $this->options;
//    }
//}
