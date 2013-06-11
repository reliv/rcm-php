/**
 * Aperture Slider Progress Indicator
 *
 * JavaScript object creates a progress indicator for multi part forms
 *
 * @author    Rod Mcnew
 * @license   License.txt New BSD License
 */

/**
 * Aperture Slider Progress Indicator Constructor
 * @param {jQuery} containerDiv
 * @param {int} stepCount
 * @constructor
 */
var ApertureProgressIndicator = function (containerDiv, stepCount) {
    for (var i = 1; i <= stepCount; i++) {
        containerDiv.append($('<span>' + i + '</span>'))
    }
    this.setProgress = function(step){
        var progressChunks = containerDiv.find('span');
        $.each(progressChunks, function (i, progressChunk) {
            i++;
            progressChunk = $(progressChunk);
            if (i < step) {
                progressChunk.addClass('completed');
                progressChunk.removeClass('current');
            } else if (i == step) {
                progressChunk.addClass('current');
                progressChunk.removeClass('completed');
            } else {
                progressChunk.removeClass('current');
                progressChunk.removeClass('completed');
            }
        });
    }
};