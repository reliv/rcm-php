<?php

namespace Rcm\Renderer;

use Zend\View\Renderer\PhpRenderer;

class RenderViewModelWithChildren
{
    protected $zendRenderer;

    public function __construct(PhpRenderer $zendRenderer)
    {
        $this->zendRenderer = $zendRenderer;
    }

    public function __invoke($viewModel)
    {
        $viewModel->setOption('has_parent', true);

        if ($viewModel->hasChildren()) {
            foreach ($viewModel->getChildren() as $child) {
                if ($viewModel->terminate() && $child->terminate()) {
                    throw new RuntimeException('Inconsistent state. Child view model is marked as terminal.');
                }
                $child->setOption('has_parent', true);
                $result = $this->__invoke($child);
                $child->setOption('has_parent', null);
                $capture = $child->captureTo();
                if (!empty($capture)) {
                    if ($child->isAppend()) {
                        $oldResult = $viewModel->{$capture};
                        $viewModel->setVariable($capture, $oldResult . $result);
                    } else {
                        $viewModel->setVariable($capture, $result);
                    }
                }
            }
        }

        $html = $this->zendRenderer->render($viewModel);

        return $html;
    }
}
