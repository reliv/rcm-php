var rcmShowPermissions = function (selectedRoles, onOkCallback)
{

    var me = this;

    me.nameSpace = $.generateUUID();

    me.selectedRoles = selectedRoles;

    me.onOkCallBack = onOkCallback;

    me.roleForm = $('<div rcm-user-role-selector="' +
        me.nameSpace +
        '" rcm-user-role-selector-id-property="roleId" ' +
        'rcm-user-role-selector-title-property="roleId" ' +
        'rcm-user-role-selector-namespace-property="namespace" ' +
        'rcm-user-role-selector-show-nesting="" ' +
        'rcm-user-role-selector-search-label="Search" ' +
        'rcm-user-role-selector-search-placeholder="Search...">' +
        '</div>'
    ).addClass('simple');

    me.buildDialog = function() {

        me.roleForm.dialog({
            title: 'Properties',
            modal: true,
            width: 620,
            open: function(){
                angular.element(me.roleForm).injector().invoke(
                    function ($compile) {
                        var scope = angular.element(me.roleForm).scope();
                        $compile(me.roleForm)(scope);
                        scope.$apply();
                    }
                );

                rcmUser.rcmUserRolesService.service.setSelectedRoles(me.nameSpace, me.selectedRoles);
            },
            buttons: {
                Cancel: function () {
                    $(this).dialog("close");
                },
                Ok: function() {
                    me.selectedRoles = rcmUser.rcmUserRolesService.service.getSelectedRoles(me.nameSpace);
                    var roles = [];

                    jQuery.each(me.selectedRoles, function(roleIndex, roleValue) {
                        roles.push(roleIndex);
                    });

                    $(this).dialog("close");

                    if (typeof me.onOkCallBack == "function") {
                        me.onOkCallBack(roles);
                    }
                }
            }
        });


    };

    rcmUser.eventManager.on(
        'rcmUserRolesService.onRolesReady',
        me.buildDialog
    );

    rcmUser.rcmUserRolesService.service.requestRoles();
};

