<?php

namespace Rcm\Acl;

class HardCodedGroups
{
    const GUEST = 'guest'; // non-logged-in people have this group
    const EVERYONE = 'everyone'; // everybody has this group whether logged in or not
}
