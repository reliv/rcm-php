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
 * @param {Integer} frameCount number of frames
 * @param {Integer} width width
 * @param {Integer} [minHeight] height
 * @constructor
 */
var ApertureSlider = function (apertureDiv, frameCount, width, minHeight) {
    //Default Values
    if (typeof(minHeight) == 'undefined') {
        minHeight = 300;
    }

    var animationDelay = 400;
    var frameSeparation = 100;


    //Get all the divs we need to work with
    var filmDiv = apertureDiv.children('div');
    var frameDivs = filmDiv.children('div');

    //Init var
    var currentFrame = 1;
    var frameChangedCallBack;
    var bbqStateId='s';

    /**
     * Always refers to me object unlike the 'me' JS variable;
     *
     * @type {ApertureSlider}
     */
    var me = this;


    /**
     * Sets the current frame. This is the meat of this class.
     *
     * @param {Integer} newFrame the frame that we want to switch to
     * @param {Function} [callBack] is called when sliding is complete
     * @param {Boolean} [skipPushState] used internally only
     */
    me.setCurrentFrame = function (newFrame, callBack, skipPushState) {

        newFrame=parseInt(newFrame);

        if (currentFrame == 0) {
            //don't allow more sliding if we are already in the middle of a slide
            return false;
        } else if(currentFrame==newFrame) {
            //If we are already here, there is no reason to move
            if(typeof(callBack)=='function'){
                callBack(currentFrame);
            }
            return true;
        } else {
            //Save the last frame that we were on so we can hide it after the
            //transition
            var lastFrame = currentFrame;

            //mark that we are currently sliding
            currentFrame = 0;


            //Show the next frame's contents
            me.getFrameDiv(newFrame).children().show();

            //Mess with the url if browser button support is on
                if(!skipPushState){
                    me.pushStateToHistory(newFrame);
                }

            filmDiv.animate(
                {
                    'margin-left':-(newFrame-1) * (width + frameSeparation)
                },
                animationDelay,
                function () {
                    //hide the previous frame's contents
                    me.getFrameDiv(lastFrame).children().hide();

                    //mark that we are done sliding
                    currentFrame = newFrame;

                    me.focusOnFirstInput();

                    //call the passed-in callback if it is set
                    if (typeof(callBack) == 'function') {
                        callBack(currentFrame);
                    }

                    //call universal frame-changed callBack if it is set
                    if (typeof(frameChangedCallBack) != 'undefined') {
                        frameChangedCallBack(currentFrame);
                    }

                }
            );

            return true;
        }

    };

    me.focusOnFirstInput = function(){
        var input=me.getCurrentFrameDiv().find('input').first();
        if(input){
            input.focus();
        }
    };

    me.getCurrentFrameDiv = function(){
        return me.getFrameDiv(currentFrame);
    };

    me.getFrameDiv = function(frameNumber){
        return $(frameDivs.get(frameNumber-1));
    };

    /**
     * Sets a callback function that will be call after each frame change
     * completes.
     *
     * @param {Function} callBack [optional] is called when sliding is complete
     */
    me.setFrameChangedCallBack = function (callBack) {
        frameChangedCallBack = callBack;
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
     * slide to next frame
     *
     * @param {Function} [callBack] is called when sliding is complete
     */
    me.goForward = function (callBack) {
        if (currentFrame < frameCount) {
            me.setCurrentFrame(currentFrame + 1, callBack);
        }
    };

    /**
     * Slide to last frame
     *
     * @param {Function} [callBack] is called when sliding is complete
     */
    me.goBack = function (callBack) {
        if (currentFrame != 1) {
            me.setCurrentFrame(currentFrame - 1, callBack);
        }
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
    me.handleHashChange = function(){
        var frame = $.bbq.getState( bbqStateId, true ) || 1;
        me.setCurrentFrame(
            parseFloat(frame),null,true
        );
    };

    /**
     * Pushes the current state (which frame we are on) to the html5 history
     * object. This is used for browser button support.
     *
     * @param frame
     */
    me.pushStateToHistory = function(frame){
        var state={};
        state[bbqStateId]=frame;
        $.bbq.pushState(state);
    };

    /**
     * Checks if a value is numeric
     *
     * @param value
     * @return {Boolean}
     */
    me.isNumeric = function(value){
        return !isNaN(value)&&isFinite(value);
    };

    me.init = function (){
        //Hide optional "Loading..." div
        apertureDiv.parent().find('.apertureLoading').hide();

        //Add css
        frameDivs.css('float', 'left');
        frameDivs.css('width', width + 'px');
        frameDivs.css('min-height', minHeight + 'px');
        frameDivs.css('margin-right', frameSeparation + 'px');
        filmDiv.css('width', +(frameCount * (width + frameSeparation)) + 'px');
        filmDiv.css('margin-left: 0');
        apertureDiv.css('width', width + 'px');
        apertureDiv.css('overflow', 'hidden');

        //Hide off-screen frame contents
        frameDivs.children().hide();
        me.getCurrentFrameDiv().children().show();

        //Focus on first input if this is a form
        me.focusOnFirstInput();

        //Support browser's refresh button
        me.handleHashChange();

        //Support browser's back button
        $(window).bind( 'hashchange', me.handleHashChange);
    };

    me.init();
};