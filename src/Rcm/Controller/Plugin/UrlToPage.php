<?php

namespace Rcm\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class UrlToPage extends AbstractPlugin
{
    /**
     * Redirect to a page
     *
     * @param string $pageName Page Name
     * @param string $pageType Page Type
     *
     * @return \Zend\Http\Response
     */
    public function __invoke($pageName, $pageType='n')
    {
        return $this->url($pageName, $pageType);
    }

    /**
     * Redirect to same page with no version numbers
     *
     * @param string $pageName Page Name
     * @param string $pageType Page Type
     *
     * @return \Zend\Http\Response
     */
    public  function url($pageName, $pageType)
    {
        $controller = $this->getController();

        if ($pageType == 'n' && $pageName == 'index') {
            return '/';
        } elseif ($pageType == 'n') {
            return $controller->url()->fromRoute(
                'contentManager',
                array('page' => $pageName)
            );
        } else {
            return $controller->url()->fromRoute(
                'contentManagerWithPageType',
                array(
                    'pageType' => $pageType,
                    'page' => $pageName,
                )
            );
        }
    }
}