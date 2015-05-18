/**
 * requires rcmApi from core
 */
angular.module('rcmAdminApi', [])
    .factory(
    'rcmAdminApiUrlService',
    [
        function () {

            /**
             * url map for APIs - These are parsed using the rcmApi service (core)
             * NOTE: these are named like {leastSpecific}{moreSpecific}{mostSpecific} for consistency and order
             *
             * @type {object}
             */
            var urlMap = {
                countries: '/api/admin/country',
                languages: '/api/admin/language',
                pageTypes: '/api/admin/pagetypes',
                /* Current site */
                siteCurrent: '/api/admin/manage-sites/current',
                /* Default site configuration */
                siteDefault: '/api/admin/manage-sites/default',
                sites: '/api/admin/manage-sites',
                site: '/api/admin/manage-sites/{siteId}',
                siteCopy: '/api/admin/site-copy',
                sitePages: '/api/admin/sites/{siteId}/pages',
                sitePage: '/api/admin/sites/{siteId}/pages/{pageId}',
                sitePageCopy: '/api/admin/sites/{siteId}/page-copy/{pageId}',

                themes: '/api/admin/theme'
            };

            return urlMap;
        }
    ]
);

rcm.addAngularModule('rcmAdminApi');