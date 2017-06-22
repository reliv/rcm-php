<?php

namespace Rcm\Repository;

use Doctrine\ORM\EntityRepository;
use Rcm\Entity\ContainerInterface as ContainerEntityInterface;
use Rcm\Entity\Revision;

/**
 * Container Repository.  Used to get custom container results from the DB
 *
 * @author    Westin Shafer <wshafer@relivinc.com>
 */
abstract class ContainerAbstract extends EntityRepository implements ContainerInterface
{
    /**
     * @param ContainerEntityInterface $container
     * @param                          $containerData
     * @param string                   $createdByUserId
     * @param string                   $createdReason
     * @param string                   $author
     * @param null                     $revisionNumber
     *
     * @return int|null
     */
    public function saveContainer(
        ContainerEntityInterface $container,
        $containerData,
        $createdByUserId,
        $createdReason = 'unknown',
        $author = 'unknown',
        $revisionNumber = null
    ) {
        $revision = null;
        $publishRevision = false;

        if (empty($revisionNumber)) {
            $revision = $container->getPublishedRevision();
            $publishRevision = true;
        } else {
            $revision = $container->getRevisionById($revisionNumber);
        }

        $md5 = md5(serialize($containerData));

        if (!empty($revision)) {
            if ($revision->getMd5() == $md5) {
                return null;
            }
        }

        $site = $container->getSite();

        $newRevision = new Revision(
            $createdByUserId,
            $createdReason
        );
        $newRevision->setAuthor($author);
        $newRevision->setMd5($md5);

        $isDirty = false;

        /** @var \Rcm\Repository\PluginWrapper $pluginWrapperRepo */
        $pluginWrapperRepo = $this->_em->getRepository(
            '\Rcm\Entity\PluginWrapper'
        );

        //Make a list of original wrappers so we can see if one was removed
        $deletedWrapperIds = [];

        if ($revision) {
            foreach ($revision->getPluginWrappers() as $wrapper) {
                $deletedWrapperIds[$wrapper->getPluginWrapperId()] = true;
            }
        }

        if (empty($containerData)) {
            $isDirty = true;
        } else {
            foreach ($containerData as $pluginData) {
                $pluginWrapper = null;

                if (!empty($revision)) {
                    /** @var \Rcm\Entity\PluginWrapper $pluginWrapper */
                    $pluginWrapper = $revision->getPluginWrapper(
                        $pluginData['instanceId']
                    );
                    if ($pluginWrapper
                        && isset(
                            $deletedWrapperIds[$pluginWrapper->getPluginWrapperId()]
                        )
                    ) {
                        unset(
                            $deletedWrapperIds[$pluginWrapper->getPluginWrapperId()]
                        );
                    }
                }

                $newPluginWrapper = $pluginWrapperRepo->savePluginWrapper(
                    $pluginData,
                    $site,
                    $pluginWrapper
                );

                $newRevision->addPluginWrapper($newPluginWrapper);

                if (!empty($pluginWrapper)
                    && $pluginWrapper->getPluginWrapperId()
                    == $newPluginWrapper->getPluginWrapperId()
                    && ($pluginWrapper->getInstance()->getInstanceId()
                        == $newPluginWrapper->getInstance()->getInstanceId()
                        || $pluginWrapper->getInstance()->isSiteWide())
                ) {
                    continue;
                }

                $isDirty = true;
            }
        }

        if (count($deletedWrapperIds)) {
            $isDirty = true;
        }

        if ($isDirty) {
            $this->_em->persist($newRevision);
            $this->_em->flush($newRevision);

            $container->addRevision($newRevision);

            if ($publishRevision) {
                $newRevision->publishRevision();
                $container->setPublishedRevision($newRevision);
            }

            $stagedRevision = $container->getStagedRevision();

            if (!empty($stagedRevision)
                && $revision->getRevisionId() == $stagedRevision->getRevisionId()
            ) {
                $container->setStagedRevision($newRevision);
            }

            $this->_em->flush($container);

            return $newRevision->getRevisionId();
        }

        return null;
    }
}
