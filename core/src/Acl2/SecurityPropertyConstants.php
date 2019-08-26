<?php

namespace Rcm\Acl2;

class SecurityPropertyConstants
{
    const TYPE_CONTENT = 'content';
    const CONTENT_TYPE_KEY = 'contentType';
    const CONTENT_TYPE_PAGE = 'page';
    const CONTENT_TYPE_SITE = 'page';

    const TYPE_INTERNATIONAL_STANDARD = 'internationalStandard';
    const INTERNATIONAL_STANDARD_TYPE_KEY = 'internationalStandardType';
    const INTERNATIONAL_STANDARD_TYPE_COUNTRY = 'country';
    const INTERNATIONAL_STANDARD_TYPE_LANGUAGE = 'language';

    const TYPE_ADMIN_TOOL = 'adminTool';
    const ADMIN_TOOL_TYPE_KEY = 'adminToolType';
    const ADMIN_TOOL_TYPE_PAGE_TYPE = 'pageType';
    const ADMIN_TOOL_TYPE_PAGE_THEME = 'theme';
    const ADMIN_TOOL_TYPE_BLOCK_INSTANCE_CONFIG = 'blockInstanceConfig';
}
