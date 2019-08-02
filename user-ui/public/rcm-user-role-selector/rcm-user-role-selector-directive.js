/**
 * rcmUserRoleSelector.rcm-user-role-selector
 * Example with all properties set
 *     <div
 *     rcm-user-role-selector="pagePermissions"
 *     rcm-user-role-selector-id-property="namespace"
 *     rcm-user-role-selector-title-property="roleId"
 *     rcm-user-role-selector-show-nesting=""
 *     rcm-user-role-selector-save-label="Save"
 *     rcm-user-role-selector-search-label="Search"
 *     rcm-user-role-selector-search-placeholder="Search..."
 *     rcm-user-role-selector-save="mySave()"
 *     >
 *     </div>
 */
angular.module('rcmUserRoleSelector').directive(
    'rcmUserRoleSelector',
    [
        '$log',
        '$http',
        '$parse',
        'getNamespaceRepeatString',
        'rcmUserRolesService',
        function ($log, $http, $parse, getNamespaceRepeatString, rcmUserRolesService) {

            var thisLink = function (scope, element, attrs) {

                var self = this;

                /**
                 * Required
                 * From attribute rcm-user-role-selector="myNamespace"
                 * Will use this value as a key to store selected roles
                 * This can be a shared key to allow sharing selected roles between apps
                 * @type {string}
                 */
                scope.valueNamespace = attrs.rcmUserRoleSelector;

                /**
                 * From attribute rcm-user-role-selector-id-property="roleId"
                 * Used to find the id of the object that relates to the index of the object in the list
                 * @type {string}
                 */
                scope.idProperty = "roleId";

                /**
                 * From attribute rcm-user-role-selector-title-property="roleId"
                 * Used to find the title property of the source object
                 * @type {string}
                 */
                scope.titleProperty = "roleId";

                /**
                 * From attribute rcm-user-role-selector-namespace-property="namespace"
                 * Used to find the namespace property of the source object
                 * @type {string}
                 */
                scope.namespaceProperty = "namespace";

                /**
                 * From attribute rcm-user-role-selector-nesting-string="-"
                 * If is set, will show a tree style of roles
                 * Require the idProperty to be the namespace in format rootId.childId1.childId2 etc
                 * @type {string}
                 */
                self.nestingString = '-';

                /**
                 * From attribute rcm-user-role-selector-save
                 * Allow a save function to be injected and fired on save
                 * @type {null}
                 */
                self.injectedSave = null;

                /**
                 * From attribute rcm-user-role-selector-save-label
                 * Blank will hide save button
                 * @type {string}
                 */
                scope.saveLabel = '';

                scope.hideSave = true;

                /**
                 * From attribute rcm-user-role-selector-search-label
                 * Blank will hide search field
                 * @type {string}
                 */
                scope.searchLabel = '';

                /**
                 * From attribute rcm-user-role-selector-search-placeholder
                 * Blank will hide search field
                 * @type {string}
                 */
                scope.searchPlaceholder = '';

                scope.hideSearch = true;

                /**
                 * loading state
                 * @type {boolean}
                 */
                scope.loading = true;

                /**
                 * roles from service
                 * @type {{}}
                 */
                scope.roles = {};

                /**
                 * selectedRoles from service
                 * @type {{}}
                 */
                scope.selectedRoles = rcmUserRolesService.getRoles();

                /**
                 * init
                 */
                self.init = function () {

                    if (typeof scope.valueNamespace !== 'string') {
                        $log.error(
                            "Attribute rcm-user-role-selector requires a value. It will be the unique name used to store cached selected roles."
                        );
                    }

                    if (typeof attrs.rcmUserRoleSelectorIdProperty === 'string' && attrs.rcmUserRoleSelectorIdProperty != '') {
                        scope.idProperty = attrs.rcmUserRoleSelectorIdProperty;
                    }

                    if (typeof attrs.rcmUserRoleSelectorTitleProperty === 'string' && attrs.rcmUserRoleSelectorTitleProperty != '') {
                        scope.titleProperty = attrs.rcmUserRoleSelectorTitleProperty;
                    }

                    if (typeof attrs.rcmUserRoleSelectorNamespaceProperty === 'string' && attrs.rcmUserRoleSelectorNamespaceProperty != '') {
                        scope.namespaceProperty = attrs.rcmUserRoleSelectorNamespaceProperty;
                    }

                    if (typeof attrs.rcmUserRoleSelectorShowNesting === 'string') {
                        self.nestingString = attrs.rcmUserRoleSelectorShowNesting
                    }

                    if (typeof attrs.rcmUserRoleSelectorSave === 'string' && attrs.rcmUserRoleSelectorSave != '') {

                        self.injectedSave = $parse(attrs.rcmUserRoleSelectorSave);
                    }

                    if (typeof attrs.rcmUserRoleSelectorSaveLabel === 'string' && attrs.rcmUserRoleSelectorSaveLabel != '') {

                        scope.saveLabel = attrs.rcmUserRoleSelectorSaveLabel;
                        scope.hideSave = false;
                    }

                    if (typeof attrs.rcmUserRoleSelectorSearchLabel === 'string') {

                        scope.searchLabel = attrs.rcmUserRoleSelectorSearchLabel;
                        scope.hideSearch = false;
                    }

                    if (typeof attrs.rcmUserRoleSelectorSearchPlaceholder === 'string') {

                        scope.searchPlaceholder = attrs.rcmUserRoleSelectorSearchPlaceholder;
                        scope.hideSearch = false;
                    }

                    scope.roles = rcmUserRolesService.getRoles();

                    rcmUser.eventManager.on(
                        'rcmUserRolesService.onRolesReady',
                        function (roles) {
                            scope.loading = false;
                            scope.roles = roles;
                            scope.selectedRoles = rcmUserRolesService.getSelectedRoles(
                                scope.valueNamespace
                            );
                            self.safeApply();
                        }
                    );

                    rcmUser.eventManager.on(
                        'rcmUserRolesService.onSetSelectedRoles',
                        function (result) {
                            scope.selectedRoles = rcmUserRolesService.getSelectedRoles(
                                scope.valueNamespace
                            );
                            self.safeApply();
                        }
                    );

                    rcmUser.eventManager.on(
                        'rcmUserRolesService.onSetSelectedRole',
                        function (result) {
                            scope.selectedRoles = rcmUserRolesService.getSelectedRoles(
                                scope.valueNamespace
                            );
                            self.safeApply();
                        }
                    );

                    // @todo - Should we call this?
                    rcmUserRolesService.requestRoles();
                };

                self.safeApply = function (fn) {
                    var phase = scope.$root.$$phase;
                    if (phase == '$apply' || phase == '$digest') {
                        if (fn && (typeof(fn) === 'function')) {
                            fn();
                        }
                    } else {
                        scope.$apply(fn);
                    }
                };

                scope.toggleSelected = function (model) {

                    var hasSelected = rcmUserRolesService.hasSelectedRole(
                        scope.valueNamespace,
                        model[scope.idProperty]
                    );

                    if (hasSelected) {
                        rcmUserRolesService.removeSelectedRole(
                            scope.valueNamespace,
                            model[scope.idProperty]
                        )
                    } else {
                        rcmUserRolesService.setSelectedRole(
                            scope.valueNamespace,
                            model[scope.idProperty],
                            model
                        );
                    }
                };

                scope.save = function () {

                    if (self.injectedSave) {
                        self.injectedSave(scope);
                        return;
                    }

                    $log.warn(
                        "No save method injected, save did nothing.",
                        scope.selectedRoles
                    );
                };

                scope.mySave = function () {
                    $log.log(
                        'Test Save',
                        'Selected Roles:',
                        scope.selectedRoles,
                        'Selected Roles Strings: ',
                        selectedRolesStrings
                    );
                };

                scope.getNestingString = function (model) {

                    if (self.nestingString) {
                        return getNamespaceRepeatString(
                            model[scope.namespaceProperty],
                            self.nestingString,
                            '.'
                        )
                    }

                    return '';
                };

                self.init();
            };

            return {
                restrict: 'A',
                link: thisLink,
                template: '' +
                '<div class="rcm-selector" ng-class="{\'loading\': loading}">' +
                ' <div class="form-group search" ng-class="{\'hide\': hideSearch}">' +
                '  <form class="form-inline">' +
                '   <label>{{searchLabel}}</label> ' +
                '   <input class="form-control" type="text" placeholder="{{searchPlaceholder}}" ng-model="searchTerm" />' +
                '   <button class="btn btn-default btn-group" data-ng-click="searchTerm = \'\'" type="button">x</button>' +
                '  </form>' +
                ' </div>' +
                ' <div class="selector">' +
                '  <ul>' +
                '   <li ng-repeat="(id, model) in roles | rcmUserRoleFilter:searchTerm">' +
                '    <div class="item" ' +
                '    ng-class="{\'selected\': selectedRoles[model[idProperty]]}" ' +
                '    ng-click="toggleSelected(model)" >' +
                '     <span>{{getNestingString(model)}}{{model[titleProperty]}}</span>' +
                '     <span class="tickMark" ng-class="{\'hide\': !(selectedRoles[model[idProperty]])}"></span>' +
                '    </div>' +
                '   </li>' +
                '  </ul>' +
                ' </div>' +
                ' <div class="save" ng-class="{\'hide\': hideSave}">' +
                '  <button class="btn btn-default" data-ng-click="save()" type="button">{{saveLabel}}</button>' +
                ' </div>' +
                '</div>'

            };
        }
    ]
);
