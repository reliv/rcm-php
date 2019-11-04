<?php

namespace Rcm\ContentConfig;

use Rcm\Entity\Page;
use Rcm\Entity\PluginInstance;
use Rcm\Entity\PluginWrapper;
use Rcm\Entity\Revision;
use Rcm\Entity\Site;

class CreatePage
{
    /** @var integer */
    protected $fakeIdCount = -1;

    public function __invoke(Site $site, array $config): Page
    {
        // Helpers::assertConfigSchema($config);
        return $this->createPageFromConfigSchemaVersion2($site, $config);
    }

    protected function createPageFromConfigSchemaVersion2(
        Site $site,
        array $config
    ): Page {
        $page = new Page('');
        $page->setSite($site);
        $page->setName($config['name']);
        $page->setPageTitle($config['title']);
        $rev = $this->createRevision(
            $config['content'],
            $config['containers'] ?? []
        );
        $page->setRevisions([$rev]);
        $page->setPublishedRevision($rev);
        $page->setCurrentRevision($rev);
        return $page;
    }

    protected function createRevision(
        array $content,
        array $containers
    ): Revision {
        $rev = new Revision('');
        $rev->setPublishedDate(new \DateTime());
        $wrappers = $this->createContainerPluginWrappers('4', $content);
        foreach ($wrappers as $wrapper) {
            $rev->addPluginWrapper($wrapper);
        }
        foreach ($containers as $name => $container) {
            $wrappers =
                $this->createContainerPluginWrappers($name, $container);
            foreach ($wrappers as $wrapper) {
                $rev->addPluginWrapper($wrapper);
            }
        }
        return $rev;
    }

    protected function createContainerPluginWrappers(
        string $container,
        array $contentConfig
    ): array {
        $wrappers = [];
        foreach ($contentConfig as $rowNumber => $row) {
            foreach ($row as $renderOrder => $blockConfig) {
                $wrappers []= $this->createPluginWrapper(
                    $container,
                    array_merge([
                        'rowNumber' => $rowNumber,
                        'renderOrder' => $renderOrder
                    ], $blockConfig)
                );
            }
        }
        return $wrappers;
    }

    protected function createPluginWrapper(
        string $container,
        array $config
    ): PluginWrapper {
        $wrapper = new PluginWrapper('');
        $wrapper->setLayoutContainer($container);
        $wrapper->setRenderOrder($config['renderOrder']);
        $wrapper->setRowNumber($config['rowNumber']);
        $wrapper->setColumnClass($config['columnClass'] ?? '');
        $wrapper->setInstance($this->createPluginInstance($config));
        return $wrapper;
    }

    protected function createPluginInstance(array $config): PluginInstance
    {
        $ins = new PluginInstance('');
        $ins->setInstanceId($this->getNextFakeId());
        $ins->setPlugin($config['block']);
        $ins->setInstanceConfig($config['config'] ?? []);
        return $ins;
    }

    protected function getNextFakeId()
    {
        return $this->fakeIdCount--;
    }
}
