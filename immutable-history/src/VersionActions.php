<?php

namespace Rcm\ImmutableHistory;

class VersionActions
{
    const CREATE_UNPUBLISHED_FROM_NOTHING = 'createUnpublishedFromNothing';
    const PUBLISH_FROM_NORTHING = 'publishFromNothing';
    const DEPUBLISH = 'depublish';
    const RELOCATE_DEPUBLISH = 'relocateDepublish';
    const RELOCATE_PUBLISH = 'relocatePublish';
    //Used when a "from" resource doesn't yet exist in the history system
    const RELOCATE_PUBLISH_FROM_UNKNOWN = 'relocatePublishFromUnknown';
    const DUPLICATE = 'duplicate';
    //Used when a "from" resource doesn't yet exist in the history system
    const DUPLICATE_FROM_UNKNOWN = 'duplicateFromUnknown';
}
