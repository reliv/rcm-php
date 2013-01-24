/**
 * Aperture Slider
 *
 * JavaScript object that can be used to create sliding multi-part forms.
 *
 * @category  ApertureSlider
 * @package   ApertureSlider
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 */

/**
 * Aperture Slider Constructor
 *
 * @param {Object} apertureDiv jQuery object for the aperture div
 * @param {Object} [configOverride] defaults are below
 * @constructor
 */
var ApertureSlider = function (apertureDiv, configOverride) {

    /**
     * Default config options
     * @type {Object}
     */
    var config = {
        frameWidth:800,
        minHeight:400,
        framesPerView:1, //Values > 1 not compatible with hideOffScreenFrames
        animationDelay:400,
        frameSeparation:100, //Must be 0 if framesPerView > 1
        hideOffScreenFrames:true, //Prevents focus in off-screen forms.
        backButtonSupport:false, //Requires jQuery bbq library
        bbqStateId:'s'
    };

    /**
     * Always refers to me object unlike the 'me' JS variable;
     *
     * @type {ApertureSlider}
     */
    var me = this;

    var filmDiv, frameDivs, currentFrame, frameCount, totalFrameWidth,
        filmWidth, apertureWidth, maxFrameIndex, minFrameIndex;

    //Allow overriding of config vars
    if (typeof(configOverride) == 'object') {
        $.extend(config, configOverride);
    }

    /**
     * Runs immediately after this class is instantiated
     */
    me.init = function () {


        //Get all the divs we need to work with
        filmDiv = apertureDiv.children();
        frameDivs = filmDiv.children();

        //Init
        currentFrame = 1;
        minFrameIndex = 1;

        //Calculate widths and counts
        frameCount = filmDiv.children().length;
        totalFrameWidth = config.frameWidth + config.frameSeparation;
        filmWidth = (frameCount * totalFrameWidth * 2); // * 2 for good measure
        apertureWidth = config.framesPerView * config.frameWidth;
        maxFrameIndex = frameCount - config.framesPerView + 1;

        //Hide optional "Loading..." div
        apertureDiv.parent().find('.apertureLoading').hide();

        //Add css
        frameDivs.css('float', 'left');
        frameDivs.css('width', config.frameWidth + 'px');
        frameDivs.css('min-height', config.minHeight + 'px');
        frameDivs.css('margin-right', config.frameSeparation + 'px');
        filmDiv.css('width', filmWidth + 'px');
        filmDiv.css('margin-left: 0');
        apertureDiv.css('width', apertureWidth + 'px');
        apertureDiv.css('overflow', 'hidden');

        //Hide off-screen frame contents
        if (config.hideOffScreenFrames) {
            frameDivs.children().hide();
            me.getCurrentFrameDiv().children().show();
        }

        //Focus on first input if this is a form
        me.focusOnFirstInput();


        if (config.backButtonSupport) {
            //Support browser's refresh button
            me.handleHashChange();

            //Support browser's back button
            $(window).bind('hashchange', me.handleHashChange);
        }
    };

    /**
     * Sets the current frame. This is the meat of this class.
     *
     * @param {Integer} newFrame the frame that we want to switch to
     * @param {Function} [callback] is called when sliding is complete
     * @param {Boolean} [skipPushState] used internally only
     */
    me.goToFrame = function (newFrame, callback, skipPushState) {

        if (newFrame < minFrameIndex) {
            newFrame = minFrameIndex;
        }

        if (newFrame > maxFrameIndex) {
            newFrame = maxFrameIndex;
        }

        if (currentFrame == 0 || !newFrame) {
            /**
             * don't allow more sliding if we are already in the middle of a
             * slide or if the new frame is invalid
             */
            return false;
        } else if (currentFrame == newFrame) {
            //If we are already here, there is no reason to move
            if (typeof(callback) == 'function') {
                callback(currentFrame);
            }
            return true;
        } else {
            //Save the last frame that we were on so we can hide it after the
            //transition
            var lastFrame = currentFrame;

            //mark that we are currently sliding
            currentFrame = 0;


            //Show the next frame's contents
            if (config.hideOffScreenFrames) {
                me.getFrameDiv(newFrame).children().show();
            }

            //Mess with the url if browser button support is on
            if (!skipPushState) {
                me.pushStateToHistory(newFrame);
            }

            filmDiv.animate(
                {
                    'margin-left':-(newFrame - 1) * totalFrameWidth
                },
                config.animationDelay,
                function () {
                    //hide the previous frame's contents
                    if (config.hideOffScreenFrames) {
                        me.getFrameDiv(lastFrame).children().hide();
                    }

                    //mark that we are done sliding
                    currentFrame = newFrame;

                    me.focusOnFirstInput();

                    //call the passed-in callback if it is set
                    if (typeof(callback) == 'function') {
                        callback(currentFrame);
                    }

                    apertureDiv.trigger('apertureFrameChanged');

                }
            );

            return true;
        }

    };

    me.focusOnFirstInput = function () {
        var input = me.getCurrentFrameDiv().find('input').first();
        if (input) {
            input.focus();
        }
    };

    me.getCurrentFrameDiv = function () {
        return me.getFrameDiv(currentFrame);
    };

    me.getFrameDiv = function (frameNumber) {
        return $(frameDivs.get(frameNumber - 1));
    };

    /**
     * Returns which frame we are currently on
     *
     * @return {Number}
     */
    me.getCurrentFrame = function () {
        return currentFrame;
    };

    /**
     *
     * @param {Integer} frameDelta amount to change frame index by
     * @param {Function} [callback] is called when sliding is complete
     */
    me.go = function (frameDelta, callback) {
        me.goToFrame(currentFrame + frameDelta, callback);
    };

    /**
     * slide to next frame
     *
     * @param {Function} [callback] is called when sliding is complete
     */
    me.goForward = function (callback) {
        me.go(1, callback);
    };

    /**
     * Slide to last frame
     *
     * @param {Function} [callback] is called when sliding is complete
     */
    me.goBack = function (callback) {
        me.go(-1, callback);
    };

    /**
     * Go forward a whole page
     *
     * @param {Function} [callback] is called when sliding is complete
     */
    me.pageForward = function (callback) {
        me.go(config.framesPerView - 1, callback)
    };

    /**
     * Go backward a whole page
     *
     * @param {Function} [callback] is called when sliding is complete
     */
    me.pageBack = function (callback) {
        me.go(0 - config.framesPerView + 1, callback)
    };

    /**
     * Gets the number of frames
     *
     * @return {Number}
     */
    me.getFrameCount = function () {
        return frameCount;
    };

    /**
     * Handle browser back, forward, and refreash buttons
     */
    me.handleHashChange = function () {
        if (config.backButtonSupport) {
            var frame = $.bbq.getState(config.bbqStateId, true) || 1;
            me.goToFrame(
                parseFloat(frame), null, true
            );
        }
    };

    /**
     * Pushes the current state (which frame we are on) to the html5 history
     * object. This is used for browser button support.
     *
     * @param frame
     */
    me.pushStateToHistory = function (frame) {
        if (config.backButtonSupport) {
            var state = {};
            state[config.bbqStateId] = frame;
            $.bbq.pushState(state);
        }
    };

    me.init();
};