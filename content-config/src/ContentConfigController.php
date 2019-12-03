<?php

namespace Rcm\ContentConfig;

use Rcm\Api\Acl\IsPageAllowedForReading;
use Rcm\Entity\Site;
use Rcm\Http\Response;
use Rcm\Page\PageData\PageDataBc;
use Rcm\Page\Renderer\PageRendererBc;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * Generates pages with content based solely on route configuration.
 */
class ContentConfigController extends AbstractActionController
{
    protected $isPageAllowedForReading;

    /** @var PageRendererBc */
    protected $renderer;

    /** @var Site */
    protected $currentSite;

    /** @var CreatePage */
    protected $createPage;

    public function __construct(
        IsPageAllowedForReading $isPageAllowedForReading,
        PageRendererBc $renderer,
        CreatePage $createPage,
        Site $currentSite
    ) {
        $this->isPageAllowedForReading = $isPageAllowedForReading;
        $this->currentSite = $currentSite;
        $this->renderer = $renderer;
        $this->createPage = $createPage;
    }

    /**
     * Block access to users without roles specified in the route config
     *
     * @param MvcEvent $e
     * @return void
     */
    public function onDispatch(MvcEvent $e)
    {
        $rolesAllowed = $this->params()->fromRoute('rolesAllowed');
        if (!empty($rolesAllowed)) {
            $allowed = $this->isPageAllowedForReading
                ->currentUserHasReadAccessToPageAccordingToAclSystem($rolesAllowed);
            if (!$allowed) {
                $response = $this->getResponse();
                $response->setStatusCode(Response::STATUS_CODE_401);
                $response->setContent($response->renderStatusLine());
                $e->setResult($response);

                return;
            }
        }
        parent::onDispatch($e);
    }

    public function contentConfigAction()
    {
        $pageConfig = [
            'name' => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'title' => $this->params()->fromRoute('title'),
            'content' => $this->params()->fromRoute('content'),
            'containers' => $this->params()->fromRoute('containers')
        ];
        $page = $this->createPage->__invoke($this->currentSite, $pageConfig);
        $pageData = new PageDataBc();
        $pageData->setPage($page);
        $pageData->setSite($page->getSite());
        // TODO: Add support for choosing other page layouts besides the default
        $pageData->setRequestedPage([
            'type' => 'n',
            'name' => $page->getName()
        ]);

        return $this->renderer->renderZf2(
            new Response(),
            $this->layout(),
            new ViewModel(),
            $pageData
        );
    }

    /**
     * Renders an instance of a client react block.
     *
     * Rendering simply generates a client-side call to the block renderer. This
     * means the named block doesn't need to be registered server-side, and doesn't
     * need a block configuration. As long as a client-side block definition exists
     * and is registered on the client you can render it.
     *
     * @return void
     */
    public function clientReactBlockAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setVariable('title', $this->params()->fromRoute('title'));
        $viewModel->setVariable('block', $this->params()->fromRoute('block'));
        $viewModel->setVariable(
            'config',
            json_encode($this->params()->fromRoute('config'))
        );
        $viewModel->setTemplate('client-react-block');

        return $viewModel;
    }
}
