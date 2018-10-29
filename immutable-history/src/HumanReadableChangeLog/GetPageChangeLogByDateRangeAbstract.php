<?php
//
//namespace Zrcms\CorePageDoctrine\Api\ChangeLog;
//
//use Zrcms\Core\Model\ActionCmsResource;
//use Zrcms\Core\Model\ChangeLogEvent;
//use Zrcms\Core\Model\ChangeLogEventBasic;
//use Zrcms\CoreApplicationDoctrine\Api\ChangeLog\GetChangeLogByDateRangeAbstract as CoreGetChangLogAbstract;
//use Zrcms\CorePageDoctrine\Entity\PageCmsResourceHistoryEntity;
//use Zrcms\CorePageDoctrine\Entity\PageVersionEntity;
//
//class GetPageChangeLogByDateRangeAbstract extends CoreGetChangLogAbstract
//{
//    protected $entityManger;
//
//    /**
//     * @return string
//     */
//    protected function getResourceHistoryEntityName(): string
//    {
//        return PageCmsResourceHistoryEntity::class;
//    }
//
//    /**
//     * @return string
//     */
//    protected function getVersionEntityName(): string
//    {
//        return PageVersionEntity::class;
//    }
//
//    /**
//     * @param PageVersionEntity $version
//     *
//     * @return ChangeLogEvent
//     * @throws \Zrcms\Core\Exception\TrackingInvalid
//     */
//    protected function versionRowToChangeLogEvent($version): ChangeLogEvent
//    {
//        $properties = $version->getProperties();
//
//        $event = new ChangeLogEventBasic();
//        $event->setDateTime($version->getCreatedDateObject());
//        $event->setUserId($version->getCreatedByUserId());
//        $event->setActionId('create');
//        $event->setActionName('created');
//        $event->setResourceId($version->getId());
//        $event->setResourceTypeName('page draft version');
//        $event->setResourceName('for ' . $properties['path']);
//        $event->setMetaData(
//            [
//                'siteCmsResourceId' => $version->getSiteCmsResourceId(),
//            ]
//        );
//
//        return $event;
//    }
//
//    /**
//     * @param PageCmsResourceHistoryEntity $historyItem
//     *
//     * @return ChangeLogEvent
//     * @throws \Exception
//     */
//    protected function resourceHistoryRowToChangeLogEvent($historyItem): ChangeLogEvent
//    {
//        $cmsResource = $historyItem->getCmsResource();
//
//        $contentVersionId = $historyItem->getContentVersionId();
//
//        switch ($historyItem->getAction()) {
//            case ActionCmsResource::PUBLISH_RESOURCE_NEW_VERSION:
//                $actionDescription = 'published draft version #' . $contentVersionId . ' to';
//                break;
//            case ActionCmsResource::PUBLISH_RESOURCE_SAME_VERSION:
//                $actionDescription = 'published draft version #' . $contentVersionId . ' to';
//                break;
//            case ActionCmsResource::UNPUBLISH_RESOURCE_NEW_VERSION:
//                $actionDescription = 'modified an unpublished version of';
//                break;
//            case ActionCmsResource::UNPUBLISH_RESOURCE_SAME_VERSION:
//                $actionDescription = 'depublished draft version #' . $contentVersionId . ' from';
//                break;
//            default:
//                throw new \Exception('Unknown action type found: ' . $historyItem->getAction());
//        }
//
//        $event = new ChangeLogEventBasic();
//        $event->setDateTime($historyItem->getCreatedDateObject());
//        $event->setUserId($historyItem->getCreatedByUserId());
//        $event->setActionId($historyItem->getAction());
//        $event->setActionName($actionDescription);
//        $event->setResourceId($cmsResource->getId());
//        $event->setResourceTypeName('page');
//        $event->setResourceName($cmsResource->getPath());
//        $event->setMetaData(
//            [
//                'siteCmsResourceId' => $cmsResource->getSiteCmsResourceId(),
//                'contentVersionId' => $historyItem->getContentVersionId()
//            ]
//        );
//
//        return $event;
//    }
//}
