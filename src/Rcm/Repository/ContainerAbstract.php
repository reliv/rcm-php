<?php

/**
 * Container Repository
 *
 * This file contains the container repository
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Rcm\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Rcm\Entity\ContainerInterface as ContainerEntityInterface;
use Rcm\Entity\Revision;

/**
 * Container Repository
 *
 * Container Repository.  Used to get custom container results from the DB
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
abstract class ContainerAbstract extends EntityRepository implements ContainerInterface
{
    public function saveContainer(
        ContainerEntityInterface $container,
        $containerData,
        $author,
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

        $newRevision = new Revision();
        $newRevision->setAuthor($author);
        $newRevision->setMd5($md5);

        $isDirty = false;

        /** @var \Rcm\Repository\PluginWrapper $pluginWrapperRepo */
        $pluginWrapperRepo = $this->_em->getRepository(
            '\Rcm\Entity\PluginWrapper'
        );

        //Make a list of original wrappers so we can see if one was removed
        $deletedWrapperIds = [];
        foreach ($revision->getPluginWrappers() as $wrapper) {
            $deletedWrapperIds[$wrapper->getPluginWrapperId()] = true;
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
                        unset($deletedWrapperIds[$pluginWrapper->getPluginWrapperId(
                        )]);
                    }
                }

                $newPluginWrapper = $pluginWrapperRepo->savePluginWrapper(
                    $pluginData,
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
                &&
                $revision->getRevisionId() == $stagedRevision->getRevisionId()
            ) {
                $container->setStagedRevision($newRevision);
            }

            $this->_em->flush($container);
            return $newRevision->getRevisionId();
        }

        return null;
    }
}
