
/**
 * RcmAdminContainer AKA RcmContainer
 * @param page
 * @param elm
 * @constructor
 */
var RcmAdminContainer = function (page, id, rcmContainerModel, onInitted) {

    var self = this;

    self.model = rcmContainerModel;

    self.page = page;
    self.id = id;
    self.editMode = false;

    /**
     * getData
     * @returns {*}
     */
    self.getData = function () {

        return self.model.getData(self.id);
    };

    /**
     * canEdit
     * @param editing
     * @returns {boolean}
     */
    self.canEdit = function (editing) {

        return (editing.indexOf(self.getData().type) > -1);
    };

    /**
     * onEditChange
     * @param args
     */
    self.onEditChange = function (args) {

        self.editMode = self.canEdit(args.editing);
    };

    /**
     * init
     */
    self.init = function (onComplete) {

        self.page.events.on('editingStateChange', self.onEditChange);

        if (typeof onComplete === 'function') {
            onComplete(self);
        }
    };

    self.init(onInitted);
};
