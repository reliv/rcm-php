var rcmColumnResize = new /** @class */ function RcmColumnResize () {
    var self = this;

    this.totalWidthColumns = 12;

    this.defaultClass = 'col-sm-12';

    /**
     * getColumnWidth
     * @param totalWidth
     * @returns {number}
     */
    this.getColumnWidthPx = function (totalWidth) {
        return (totalWidth / self.totalWidthColumns);
    };

    /**
     * getPartWidthColumns
     * @param totalWidthPx
     * @param partWidthPx
     * @returns {number}
     */
    this.getPartWidthColumns = function (totalWidthPx, partWidthPx) {

        var columnWidthPx = self.getColumnWidthPx(totalWidthPx);

        var partWidthColumns = Math.ceil(partWidthPx / columnWidthPx);

        return partWidthColumns;
    };

    /**
     * getMediaView
     * @returns {string}
     */
    this.getMediaView = function () {
        // @todo Make this work by getting the data from some source
        return 'sm'
    };

    /**
     *
     * @param elm
     * @param widthCols
     */
    this.setWidth = function (elm, widthCols) {

        var mediaView = self.getMediaView();

        var columnData = self.getElmColumnData(elm);

        var maxWidthColumns = self.totalWidthColumns - columnData[mediaView].offset;

        if (widthCols > maxWidthColumns) {
            widthCols = maxWidthColumns;
        }

        if (widthCols < 1) {
            widthCols = 1;
        }

        var widthAndOffset = widthCols + columnData[mediaView].offset;

        if (widthAndOffset > self.totalWidthColumns) {
            columnData[mediaView].offset = self.totalWidthColumns - widthCols;
        }

        columnData[mediaView].width = widthCols;

        self.updateColumnClass(
            elm,
            columnData
        );
    };

    /**
     * setOffset in columns
     * @param elm
     * @param offsetCols
     */
    this.setOffset = function (elm, offsetCols) {

        var mediaView = self.getMediaView();

        var columnData = self.getElmColumnData(elm);

        var maxOffsetColumns = self.totalWidthColumns - 1; //columnData[mediaView].width;

        if (offsetCols > maxOffsetColumns) {
            offsetCols = maxOffsetColumns;
        }

        if (offsetCols < 0) {
            offsetCols = 0;
        }

        var widthAndOffset = offsetCols + columnData[mediaView].width;

        if (widthAndOffset > self.totalWidthColumns) {
            columnData[mediaView].width = self.totalWidthColumns - offsetCols;
        }

        columnData[mediaView].offset = offsetCols;

        self.updateColumnClass(
            elm,
            columnData
        );
    };

    /**
     * setVisible
     * @param elm
     * @param visible
     */
    this.setVisible = function (elm, visible) {

        var mediaView = self.getMediaView();

        var columnData = self.getElmColumnData(elm);
        columnData[mediaView].visible = visible;

        self.updateColumnClass(
            elm,
            columnData
        );
    };

    /**
     * getVisible
     * @param elm
     */
    this.getVisible = function (elm) {

        var columnData = self.getElmColumnData(elm);

        var mediaView = self.getMediaView();

        return columnData[mediaView].visible;
    };

    /**
     * setHidden
     * @param elm
     * @param hidden bool
     */
    this.setHidden = function (elm, hidden) {

        var mediaView = self.getMediaView();

        var columnData = self.getElmColumnData(elm);
        columnData[mediaView].hidden = hidden;

        self.updateColumnClass(
            elm,
            columnData
        );
    };

    /**
     * getHidden
     * @param elm
     */
    this.getHidden = function (elm) {

        var columnData = self.getElmColumnData(elm);

        var mediaView = self.getMediaView();

        return columnData[mediaView].hidden;
    };

    /**
     * getElmColumnData
     * @param elm
     * @returns {{xs: {width: number, offset: number}, sm: {width: number, offset: number}, md: {width: number, offset: number}, lg: {width: number, offset: number}}}
     */
    this.getElmColumnData = function (elm) {

        var currentClass = self.getCurrentClass(elm);

        currentClass = currentClass.replace(/^\s+|\s+$/g, '');

        var classes = currentClass.split(' ');

        var data = {
            'xs': {
                width: 0,
                offset: 0,
                visible: '',
                hidden: false
            },
            'sm': {
                width: 0,
                offset: 0,
                visible: '',
                hidden: false
            },
            'md': {
                width: 0,
                offset: 0,
                visible: '',
                hidden: false
            },
            'lg': {
                width: 0,
                offset: 0,
                visible: '',
                hidden: false
            }
        };

        var part;

        for (var index in classes) {

            part = classes[index].split('-');

            if (part[0] === 'col') {

                if (part.length === 3) {
                    data[part[1]].width = Number(part[2]);
                }

                if (part.length === 4) {
                    data[part[1]][part[2]] = Number(part[3]);
                }
            }

            if (part[0] === 'visible') {

                var part3 = '';
                if(part[3]) {
                    part3 = part[3];
                }

                data[part[1]]['visible'] = part3;

                data[part[1]]['hidden'] = false;
            }

            if (part[0] === 'hidden') {

                data[part[1]]['visible'] = '';

                data[part[1]]['hidden'] = true;
            }
        }

        return data;
    };

    /**
     * Destroy resize bits
     * @param elm
     */
    this.destroy = function (elm) {
        jQuery(window).unbind('mousemove').unbind('mousedown');
        var controls = elm.find('.rcm-column-resize-control');
        controls.remove();
    };

    /**
     * Add draggy controls
     * @param elm
     */
    this.addControls = function (elm) {

        elm = jQuery(elm);

        try {
            // prevent duplicate create
            self.destroy(elm);
        } catch (e) {
            // nothing
        }

        var controlOffset = jQuery('<div class="rcm-column-resize-control offset"><div>&nbsp;</div></div>');

        var controlWidth = jQuery('<div class="rcm-column-resize-control width"><div>&nbsp;</div></div>');

        elm.prepend(controlWidth);
        elm.prepend(controlOffset);

        controlOffset.mousedown(
            function (e) {
                e.preventDefault();
                elm.currentColumnData = self.getElmColumnData(elm);
                elm.offsetStartPositionX = e.pageX;

                jQuery(window).mousemove(
                    function (e) {
                        var changePx = e.pageX - elm.offsetStartPositionX;

                        var changeCols = self.getPartWidthColumns(
                            elm.parent().width(),
                            changePx
                        );

                        var mediaView = self.getMediaView();

                        var cols = elm.currentColumnData[mediaView].offset + changeCols;

                        self.setOffset(elm, cols);
                    }
                );
            }
        );

        controlWidth.mousedown(
            function (e) {
                e.preventDefault();
                elm.currentColumnData = self.getElmColumnData(elm);
                elm.widthStartPositionX = e.pageX;

                jQuery(window).mousemove(
                    function (e) {
                        var changePx = e.pageX - elm.widthStartPositionX;

                        var changeCols = self.getPartWidthColumns(
                            elm.parent().width(),
                            changePx
                        );

                        var mediaView = self.getMediaView();

                        var cols = elm.currentColumnData[mediaView].width + changeCols;

                        self.setWidth(elm, cols);
                    }
                );
            }
        );

        jQuery(window).mouseup(
            function (e) {
                jQuery(window).unbind('mousemove');
            }
        );
    };

    /**
     * buildClass
     * @param columnData
     * @returns {string}
     */
    this.buildClass = function (columnData) {

        var classes = '';

        var className = '';

        for (var mediaView in columnData) {

            for (var detail in columnData[mediaView]) {

                className = '';

                if (detail == 'width' && columnData[mediaView][detail] !== 0) {

                    className = 'col' + '-' + mediaView + '-' + columnData[mediaView][detail];
                }

                if (detail == 'offset' && columnData[mediaView][detail] !== 0) {

                    className = 'col' + '-' + mediaView + '-' + detail + '-' + columnData[mediaView][detail];
                }

                if (detail == 'visible' && columnData[mediaView][detail] !== '') {
                    className = 'visible' + '-' + mediaView + '-' + columnData[mediaView][detail];
                }

                if (detail == 'hidden' && columnData[mediaView][detail]) {
                    className = 'hidden' + '-' + mediaView;
                }

                if (className !== '') {
                    classes = classes + className + ' ';
                }
            }
        }

        classes = classes.replace(/^\s+|\s+$/g, '');

        if (classes == '') {
            classes = self.defaultClass;
        }

        return classes;
    };

    /**
     * updateColumnClass
     * @param elm
     * @param columnData
     */
    this.updateColumnClass = function (elm, columnData) {
        var newClass = self.buildClass(columnData);
        self.setClass(elm, newClass);
    };

    /**
     * clearClass
     * @param elm
     */
    this.clearClass = function (elm) {

        self.setClass(elm, self.defaultClass);
    };

    /**
     *
     * @param elm
     * @returns {*}
     */
    this.getCurrentClass = function (elm) {

        return rcmAdminService.model.RcmPluginModel.getColumnClass(elm);
    };

    /**
     * Set Class
     * @param elm
     * @param newClass
     */
    this.setClass = function (elm, newClass) {
        rcmAdminService.model.RcmPluginModel.setColumnClass(elm, newClass);
        jQuery(window).trigger('resize');
    };

    this.init = self.addControls;
};

