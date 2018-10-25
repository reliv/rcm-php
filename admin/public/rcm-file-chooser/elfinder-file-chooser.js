/**
 * elfinderFileChooser
 * @type {RcmFileChooser}
 */
var elfinderFileChooser = new function () {
    /**
     * self
     * @type {elfinderFileChooser}
     */
    var self = this;

    /**
     * name
     * @type {string}
     */
    var name = 'elfinder';

    /**
     * Unique name
     * @returns {string}
     */
    self.getName = function () {
        return name;
    };

    /**
     * chooseFile
     * @param onFileChosenCallback
     * @param oldPath
     * @param filter
     */
    self.chooseFile = function (onFileChosenCallback, oldPath, filter) {

        if (!filter) {
            filter = {}
        }

        var fileType = '';

        if (filter.fileType) {
            fileType = filter.fileType;
        }

        window['elFinderFileSelected'] = function (url) {
            onFileChosenCallback(url);
        };
        //Open the file picker window
        var url = '/elfinder';

        if (fileType) {
            url += '/' + fileType;
        }

        popup(url, 1024, 768);
    };

    /**
     * @todo this needs a fresh look
     *
     * @param url
     * @param width
     * @param height
     * @param options
     * @returns {boolean}
     */
    var popup = function (url, width, height, options) {
        width = width || '80%';
        height = height || '70%';

        if (typeof width == 'string' && width.length > 1 && width.substr(
                width.length - 1,
                1
            ) == '%')
            width = parseInt(window.screen.width * parseInt(width, 10) / 100, 10);

        if (typeof height == 'string' && height.length > 1 && height.substr(
                height.length - 1,
                1
            ) == '%')
            height = parseInt(
                window.screen.height * parseInt(height, 10) / 100,
                10
            );

        if (width < 640)
            width = 640;

        if (height < 420)
            height = 420;

        var top = parseInt(( window.screen.height - height ) / 2, 10),
            left = parseInt(( window.screen.width - width ) / 2, 10);

        options = ( options || 'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes' ) +
            ',width=' + width +
            ',height=' + height +
            ',top=' + top +
            ',left=' + left;

        var popupWindow = window.open('', null, options, true);

        // Blocked by a popup blocker.
        if (!popupWindow)
            return false;

        try {
            // Chrome 18 is problematic, but it's not really needed here (#8855).
            var ua = navigator.userAgent.toLowerCase();
            if (ua.indexOf(' chrome/18') == -1) {
                popupWindow.moveTo(left, top);
                popupWindow.resizeTo(width, height);
            }
            popupWindow.focus();
            popupWindow.location.href = url;
        }
        catch (e) {
            popupWindow = window.open(url, null, options, true);
        }

        return true;
    };
};

/**
 * Set ElFinder as default file chooser
 * This can be over-ridden
 */
angular.module('rcmFileChooser').run(
    [
        'rcmFileChooserService',
        function (rcmFileChooserService) {
            rcmFileChooserService.setDefaultFileChooser(
                elfinderFileChooser
            )
        }
    ]
);

