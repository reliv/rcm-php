<?php

namespace Rcm\Repository;

use Doctrine\ORM\EntityRepository;
use Rcm\Entity\ContainerInterface as ContainerEntityInterface;
use Rcm\Entity\Revision;
use Rcm\Tracking\Model\Tracking;

/**
 * @deprecated Repository should not be used directly, please use the /Rcm/Api/{model}/Repository functions
 * Container Repository.  Used to get custom container results from the DB
 *
 * @author    Westin Shafer <wshafer@relivinc.com>
 */
abstract class ContainerAbstract extends EntityRepository implements ContainerInterface
{
    /**
     * @param ContainerEntityInterface $container
     * @param array $containerData
     * @param string $modifiedByUserId
     * @param string $modifiedReason
     * @param string $author
     * @param null $revisionNumber
     *
     * @return int|null
     */
    public function saveContainer(
        ContainerEntityInterface $container,
        $containerData,
        $modifiedByUserId,
        $modifiedReason = Tracking::UNKNOWN_REASON,
        $author = Tracking::UNKNOWN_AUTHOR,
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
            $modifiedByUserId,
            $modifiedReason
        );
        $newRevision->setAuthor($author);
        $newRevision->setMd5($md5);

        $isDirty = false;

        /** @var \Rcm\Repository\PluginWrapper $pluginWrapperRepo */
        $pluginWrapperRepo = $this->_em->getRepository(
            \Rcm\Entity\PluginWrapper::class
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
                    $modifiedByUserId,
                    $modifiedReason,
                    $pluginWrapper
                );

                $newRevision->addPluginWrapper($newPluginWrapper);

                if (!empty($pluginWrapper)
                    && $pluginWrapper->getPluginWrapperId()
                    == $newPluginWrapper->getPluginWrapperId()
                    && ($pluginWrapper->getInstance()->getInstanceId()
                        == $newPluginWrapper->getInstance()->getInstanceId()
                        || $pluginWrapper->getInstance()->isSiteWide()) //@deprecated <deprecated-site-wide-plugin>
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

            if (!empty($stagedRevision) && !empty($revision)
                && $revision->getRevisionId() == $stagedRevision->getRevisionId()
            ) {
                $container->setStagedRevision($newRevision);
            }

            $container->setModifiedByUserId(
                $modifiedByUserId,
                $modifiedReason
            );

            $this->_em->flush($container);

            return $newRevision->getRevisionId();
        }

        return null;
    }
}
