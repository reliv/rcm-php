/**
 * rcmFileChooserService
 * @type {rcmFileChooserService}
 */
var rcmFileChooserService = new function () {
    /**
     * self
     * @type {rcmFileChooserService}
     */
    var self = this;

    /**
     * collection of RcmFileChooser
     * @type {{DEFAULT: RcmFileChooser}}
     */
    var rcmFileChoosers = {
        DEFAULT: new RcmFileChooser()
    };

    /**
     * setDefaultFileChooser
     * @param  {RcmFileChooser} rcmFileChooser
     */
    self.setDefaultFileChooser = function (rcmFileChooser) {
        self.addFileChooser(rcmFileChooser);

        if (rcmFileChoosers.DEFAULT.getName() !== 'NONE') {
            // console.warn('Default rcmFileChooser set more than once.');
        }

        rcmFileChoosers.DEFAULT = rcmFileChooser;
    };

    /**
     *
     * @returns {RcmFileChooser}
     */
    self.getDefaultFileChooser = function () {
        return rcmFileChoosers.DEFAULT;
    };

    /**
     * addFileChooser
     * @param {RcmFileChooser} rcmFileChooser
     */
    self.addFileChooser = function (rcmFileChooser) {
        rcmFileChoosers[rcmFileChooser.getName()] = rcmFileChooser;
    };

    /**
     * getFileChooser
     * @param name
     * @returns {RcmFileChooser}
     */
    self.getFileChooser = function (name) {

        if (!rcmFileChoosers[name]) {
            console.error('RcmFileChooser not found or not configured');
            return null;
        }

        return rcmFileChoosers[name];
    };

    /**
     *
     * @param onFileChosenCallback
     * @param oldPath
     * @param filter
     */
    self.chooseFile = function (onFileChosenCallback, oldPath, filter) {
        self.getDefaultFileChooser().chooseFile(
            onFileChosenCallback,
            oldPath,
            filter
        );
    };

    /**
     * chooseFileUsing
     * @param fileChooserName
     * @param onFileChosenCallback
     * @param oldPath
     * @param filter
     */
    self.chooseFileUsing = function (fileChooserName, onFileChosenCallback, oldPath, filter) {
        var fileChooser = self.getFileChooser(fileChooserName);

        if (!fileChooser) {
            return;
        }

        fileChooser.chooseFile(onFileChosenCallback, oldPath, filter);
    };
};

angular.module('rcmFileChooser').service(
    'rcmFileChooserService',
    function () {
        return rcmFileChooserService;
    }
);
