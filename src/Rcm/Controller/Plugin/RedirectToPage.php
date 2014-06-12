<?php

namespace Rcm\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class RedirectToPage extends AbstractPlugin
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
        return $this->redirect($pageName, $pageType);
    }

    /**
     * Redirect to same page with no version numbers
     *
     * @param string $pageName Page Name
     * @param string $pageType Page Type
     *
     * @return \Zend\Http\Response
     */
    public  function redirect($pageName, $pageType)
    {
        $controller = $this->getController();

        if ($pageType == 'n' && $pageName == 'index') {
            return $controller->redirect()->toUrl(
                '/'
            );
        } elseif ($pageType == 'n') {
            return $controller->redirect()->toRoute(
                'contentManager',
                array('page' => $pageName)
            );
        } else {
            return $controller->redirect()->toRoute(
                'contentManagerWithPageType',
                array(
                    'pageType' => $pageType,
                    'page' => $pageName,
                )
            );
        }
    }
}