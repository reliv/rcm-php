<?php

namespace Rcm\ImmutableHistory;

class VersionActions
{
    const CREATE_UNPUBLISHED = 'createUnpublished';
    const PUBLISH = 'publish';
    const DEPUBLISH = 'depublish';
    const RELOCATE_DEPUBLISH = 'relocateDepublish';
    const RELOCATE_PUBLISH = 'relocatePublish';
    const DUPLICATE = 'duplicate';
    const DEFAULT_ACTION_DESCRIPTIONS = [
        self::CREATE_UNPUBLISHED => 'created an unpublished version',
        self::PUBLISH => 'published',
        self::DEPUBLISH => 'depublished',
        self::DUPLICATE => 'published as part of a copy operation',
        self::RELOCATE_DEPUBLISH => 'depublished as part of a move operation',
        self::RELOCATE_PUBLISH => 'published as part of a move operation'
    ];
}
