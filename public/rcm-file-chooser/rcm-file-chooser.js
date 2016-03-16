var RcmFileChooser = function () {

    /**
     * self
     * @type {RcmFileChooser}
     */
    var self = this;

    /**
     * name
     * @type {string}
     */
    var name = 'NONE';

    /**
     * Unique name
     * @returns {string}
     */
    self.getName = function () {
        return name;
    };

    /**
     * Do file choosing action
     *
     * @param onFileChosenCallback Callback with new file URL
     * @param oldPath              Current value
     * @param filter               Filter object
     */
    self.chooseFile = function (onFileChosenCallback, oldPath, filter) {
        console.error('No RcmFileChooser configured');
    }
};
