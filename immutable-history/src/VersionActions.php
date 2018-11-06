<?php

namespace Rcm\ImmutableHistory;

class VersionActions
{
    const CREATE_UNPUBLISHED_FROM_NOTHING = 'createUnpublished';
    const PUBLISH_FROM_NORTHING = 'publish';
    const DEPUBLISH = 'depublish';
    const RELOCATE_DEPUBLISH = 'relocateDepublish';
    const RELOCATE_PUBLISH = 'relocatePublish';
    const DUPLICATE = 'duplicate';
}
