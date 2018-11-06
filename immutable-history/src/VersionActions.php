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
}
