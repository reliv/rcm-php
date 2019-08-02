/**
 * rcmuserAdminAclApp.resourceFilter
 */
angular.module('rcmuserAdminAclApp').filter(
    'resourceFilter', 
    function () {

        var maxResults = 10;

        return function (input, query) {

            if (!query) {
                return []
            }
            if (query.length < 2) {
                return []
            }

            var result = [];
            var regex = new RegExp(query, 'i');
            for (var key in input) {
                var resource = input[key];
                if (regex.test("" + resource.resource.resourceId)
                    || regex.test("" + resource.resource.name)
                    || regex.test("" + resource.resource.description)) {
                    result.push(resource);
                }
                if (result.length >= maxResults) {
                    break;
                }
            }

            return result;
        };
    }
);
