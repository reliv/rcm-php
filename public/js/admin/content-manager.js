var rcmEdit = new RcmEdit(rcmConfig);

/**
 * Main Javascript file for the content manager.  This file is required for
 * the admin side of the content manager and is pulled in through the admin
 * layout template.
 *
 * @type {Object}
 */
function RcmEdit(config) {


    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmEdit}
     */
    var me = this;

    /**
     * Keep a list of all the called plugins so you can call their save
     * method during the save process.
     *
     * @type {Array}
     */
    me.calledPlugins = [];

    /**
     * Used to store a list of all the page plugins to use for page edits
     *
     * @type {Object}
     */
    me.pagePluginEdits = {};

    me.activeRichEditors = [];

    me.activeTextEditors = [];

    me.page = '';

    me.language = '';

    me.pageRevision = '';

    me.dropped = false;
    me.draggable_sibling = null;

    me.newInstanceId = -1;

    me.editActive = false;

    // enable transparent overlay on FF/Linux
    $.blockUI.defaults.applyPlatformOpacityRules = false;

    /**
     * Initiate the Content Manager.
     */
    me.initRCM = function() {
        me.addCkToolbars();
        me.addEditButtons();
        me.addLayoutEditor();
        me.attachEditSitePluginsClick();
        me.editPagePropertiesClick();
        me.createNewBlankPageClick();
        me.saveAsTemplate();
        me.createNewFromTemplateClick();
        $("#RcmRealPage").hide();
        $("#RcmRealPage").show();
        $("#rcmAdminTitleBarMenu li").click(function(){
            $("#rcmAdminTitleBarMenu li ul").toggle();
        });

        me.checkForLayoutEditorDisplay();
    };

    /**
     * Show the layout editor for new page creation.
     */
    me.checkForLayoutEditorDisplay = function() {
        var myParams = rcm.getUrlParams();

        if (myParams == undefined || myParams.rcmShowLayoutEditor == undefined) {
            return;
        }

        if(myParams.rcmShowLayoutEditor == 'Y') {
            me.showLayoutEditor();
        }
    };

    /**
     * Check the page name and make sure a page by the name provided doesn't
     * already exist.
     *
     * @param inputField Input field to use for the check
     * @param resultContainer The conainter where the result should be displayed
     * @return {*}
     */
    me.checkPageName = function(inputField, resultContainer) {

        /* Get the value of the input field and filter */
        var pageUrl = $(inputField).val().toLowerCase().replace(/\s/g, '-').replace(/[^A-Za-z0-9\-\_]/g, "");
        $(inputField).val(pageUrl);

        /* make sure that the page name is greater then 1 char */
        if(pageUrl.length < 1) {
            $(resultContainer).removeClass('ui-icon-check')
            $(resultContainer).addClass('ui-icon-alert').addClass('ui-icon')
            $(inputField).addClass('RcmErrorInputHightlight');
            $(inputField).removeClass('RcmOkInputHightlight');
            $(resultContainer).html('');
            return;
        }

        /* Check name via rest service */
        var pageOk = false;

        $.getJSON('/rcm-admin-checkpage/'+me.language, { pageUrl: pageUrl }, function(data) {
            if (data.pageOk == 'Y') {
                $(resultContainer).removeClass('ui-icon-alert')
                $(resultContainer).addClass('ui-icon-check').addClass('ui-icon');
                $(inputField).removeClass('RcmErrorInputHightlight');
                $(inputField).addClass('RcmOkInputHightlight');
            } else if(data.pageOk != 'Y') {
                $(resultContainer).removeClass('ui-icon-check')
                $(resultContainer).addClass('ui-icon-alert').addClass('ui-icon');
                $(inputField).addClass('RcmErrorInputHightlight');
                $(inputField).removeClass('RcmOkInputHightlight');
            } else {
                $(resultContainer).html('<p style="color: #FF0000;">Error!</p>');
                $(inputField).addClass('RcmErrorInputHightlight');
                $(inputField).removeClass('RcmOkInputHightlight');
            }
        }).error(function(){
            $(resultContainer).html('<p style="color: #FF0000;">Error!</p>');
                $(inputField).addClass('RcmErrorInputHightlight');
                $(inputField).removeClass('RcmOkInputHightlight');
        })

        return pageOk;
    };

    /**
     * Check the user name and make sure a user name by the name provided doesn't
     * already exist.
     *
     * @param inputField Input field to use for the check
     * @param resultContainer The container where the result should be displayed
     * @return {*}
     */
    me.checkUserName = function(inputField, resultContainer) {

        /* Get the value of the input field and filter */
        var userName = $(inputField).val().toLowerCase().replace(/\s/g, '').replace(/[^@A-Za-z0-9\-\_]/g, "");
        $(inputField).val(userName);

        /* make sure that the user name is greater then 0 char */
        if(userName.length < 0) {
            $(resultContainer).removeClass('ui-icon-check');
            $(resultContainer).addClass('ui-icon-alert').addClass('ui-icon');
            $(inputField).addClass('RcmErrorInputHightlight');
            $(inputField).removeClass('RcmOkInputHightlight');
            $(resultContainer).html('');
            return;
        }

        /* Check name via rest service */
        var userOk = false;

        $.getJSON('/rcm-admin-check-user/'+me.language, { pageUrl: pageUrl }, function(data) {
            if (data.userOk == 'Y') {
                $(resultContainer).removeClass('ui-icon-alert');
                $(resultContainer).addClass('ui-icon-check').addClass('ui-icon');
                $(inputField).removeClass('RcmErrorInputHightlight');
                $(inputField).addClass('RcmOkInputHightlight');
            } else if(data.userOk != 'Y') {
                $(resultContainer).removeClass('ui-icon-check');
                $(resultContainer).addClass('ui-icon-alert').addClass('ui-icon');
                $(inputField).addClass('RcmErrorInputHightlight');
                $(inputField).removeClass('RcmOkInputHightlight');
            } else {
                $(resultContainer).html('<p style="color: #FF0000;">Error!</p>');
                $(inputField).addClass('RcmErrorInputHightlight');
                $(inputField).removeClass('RcmOkInputHightlight');
            }
        }).error(function(){
                $(resultContainer).html('<p style="color: #FF0000;">Error!</p>');
                $(inputField).addClass('RcmErrorInputHightlight');
                $(inputField).removeClass('RcmOkInputHightlight');
            });

        return userOk;
    };

    /* Freeze page */
    me.blockUI = function(message){
        $.blockUI({
            message:'<span class="rcmBlockUi">'+message+'</span>',
            css:{ backgroundColor:'transparent', borderColor:'transparent'}
        });
    }

    /* Show and Edit page properties */
    me.editPagePropertiesClick = function() {
        $(".rcmPageProperties").click(function(){
            $(".editButton").trigger('click');
            var pageTitle = $('title');
            var pageKeywords = $('meta[name="keywords"]');
            var pageDesc = $('meta[name="description"]');

            //Show the dialog
            var form = $('<form>')
                .addInput('metaTitle', 'Page Title', $(pageTitle).html())
                .addInput('metaDesc',  'Page Description', $(pageDesc).attr('content'))
                .addInput('metaKeywords',  'Keywords', $(pageKeywords).attr('content'))
                .dialog({
                    title:'Properties',
                    modal:true,
                    width:620,
                    buttons:{
                        Cancel:function () {

                            $(this).dialog("close");
                        },
                        Ok:function () {

                            //Grab the non-jquery form so we can get its fields
                            var domForm = form.get(0);

                            $('title').html(domForm.metaTitle.value);
                            $('meta[name="description"]').attr('content', domForm.metaDesc.value);
                            $('meta[name="keywords"]').attr('content', domForm.metaKeywords.value);

                            $(this).dialog("close");
                        }
                    }
                });

        })
    };

    me.saveAsTemplate = function() {
        $('.saveAsTemplate').click(function(){
            $("#rcmSaveTemplateWizard").css('left', 0).css('position', 'static').dialog({
                title: 'Save as Template',
                width: 400,
                modal: true,
                zIndex: 999999,
                buttons:{
                    Cancel:function () {
                        $(this).dialog("close");
                    },
                    Ok:function () {
                        var pageName = $("#rcmTemplateNameInput").val();
                        var revisionId = me.pageRevision;

                        $.getJSON('/rcm-admin-save-as-template/'+me.language,
                            {
                                pageName: pageName,
                                revision: revisionId
                            },
                            function(data) {
                                if (data.pageOk == 'Y' && data.redirect) {
                                    window.location = data.redirect;
                                } else if(data.pageOk != 'Y' && data.error != '') {
                                    $("#rcmSaveTemplateError").html('<br /><p style="color: #FF0000;">'+data.error+'</p><br />').show();
                                } else {
                                    $("#rcmSaveTemplateError").html('<br /><p style="color: #FF0000;">Communication Error!</p><br />').show();
                                }
                            }
                        ).error(function(){
                                $("#rcmSaveTemplateError").html('<br /><p style="color: #FF0000;">Communication Error!</p><br />').show();
                            })
                    }
                }
            });

            $('#rcmTemplateNameInput').keyup(function(){
                var validationContainer = $("#newSaveTemplateIndicator");
                me.checkPageName(this, validationContainer);
            });
        })
    };

    me.createNewBlankPageClick = function() {
        $(".blankPageIcon").click(function(){

            $(".rcmNewPageLayoutContainer").click(function(){
                $(".rcmNewPageLinkOverlay").removeClass("rcmNewPageLinkOverlayActive");
                $(this).find(".rcmNewPageLinkOverlay").addClass("rcmNewPageLinkOverlayActive");
                var selectedValue = $(this).find(".rcmLayoutKeySelector").attr('name');
                $("#rcmNewPageSelectedLayout").val(selectedValue);
            })

            $("#rcmNewPageWizard").css('left', 0).css('position', 'static').dialog({
                title: 'Create a New Page',
                width: 725,
                modal: true,
                zIndex: 999999,
                buttons:{
                    Cancel:function () {

                        $(this).dialog("close");
                    },
                    Ok:function () {
                        var pageUrl = $("#rcmNewPageUrl").val();
                        var pageName = $("#rcmNewPageName").val();
                        var selectedLayout = $("#rcmNewPageSelectedLayout").val();

                        $.getJSON('/rcm-admin-create-blank-page/'+me.language,
                            {
                                pageUrl: pageUrl,
                                pageName: pageName,
                                selectedLayout: selectedLayout

                            },
                            function(data) {
                                if (data.pageOk == 'Y' && data.redirect) {
                                    window.location = data.redirect;
                                } else if(data.pageOk != 'Y' && data.error != '') {
                                    $("#rcmNewPageErrorLine").html('<br /><p style="color: #FF0000;">'+data.error+'</p><br />').show();
                                } else {
                                    $("#rcmNewPageErrorLine").html('<br /><p style="color: #FF0000;">Communication Error!</p><br />').show();
                                }
                            }
                        ).error(function(){
                            $("#rcmNewPageErrorLine").html('<br /><p style="color: #FF0000;">Communication Error!</p><br />').show();
                        })
                    }
                }
            });

            $('#rcmNewPageUrl').keyup(function(){
                var validationContainer = $("#newPageValidatorIndicator");
                me.checkPageName(this, validationContainer);
            });
        });
    };

    me.createNewFromTemplateClick = function() {
        $(".pageTemplateIcon").click(function(){

            $("#rcmNewFromTemplateWizard").css('left', 0).css('position', 'static').dialog({
                title: 'Create a New Page From a Template',
                width: 525,
                modal: true,
                zIndex: 999999,
                buttons:{
                    Cancel:function () {

                        $(this).dialog("close");
                    },
                    Ok:function () {
                        var pageUrl = $("#rcmNewFromTemplateUrl").val();
                        var pageName = $("#rcmNewFromTemplateName").val();
                        var revision = $("#rcmPageRevision").val();

                        $.getJSON('/rcm-admin-create-from-template/'+me.language,
                            {
                                pageUrl: pageUrl,
                                pageName: pageName,
                                revision: revision

                            },
                            function(data) {
                                if (data.pageOk == 'Y' && data.redirect) {
                                    window.location = data.redirect;
                                } else if(data.pageOk != 'Y' && data.error != '') {
                                    $("#rcmNewFromTemplateErrorLine").html('<br /><p style="color: #FF0000;">'+data.error+'</p><br />').show();
                                } else {
                                    $("#rcmNewFromTemplateErrorLine").html('<br /><p style="color: #FF0000;">Communication Error!</p><br />').show();
                                }
                            }
                        ).error(function(){
                                $("#rcmNewFromTemplateErrorLine").html('<br /><p style="color: #FF0000;">Communication Error!</p><br />').show();
                            })
                    }
                }
            });

            $('#rcmNewFromTemplateUrl').keyup(function(){
                var validationContainer = $("#rcmNewFromTemplateValidatorIndicator");
                me.checkPageName(this, validationContainer);
            });
        });
    }

    me.setPageProperties = function() {
        var pageTitle = $('title');
        var pageKeywords = $('meta[name="keywords"]');
        var pageDesc = $('meta[name="description"]');
        var favIcon = $('link[rel="shortcut icon"]');
    };

    me.getNewInstanceId = function() {
        var newInstanceId = me.newInstanceId;
        me.newInstanceId--;
        return newInstanceId;
    };

    me.disableLink = function (container) {
        $(container).unbind('click').click(
            function(){
                $(this).preventDefault();
                return false;
            }
        ).css('color', '#DCDCDC');

    }

    /**
     * Create CK Editors for areas that need them.
     */
    me.switchEditors = function(/* siteWide */) {

        var siteWide = false;

        if (arguments.length == 1) {
            siteWide = arguments[0];
        }

        $('#RcmRealPage [data-richEdit]').each(function() {

            //Get Inner HTML to use for TextArea
            var htmlToAddToTextArea = $(this).html();

            var instanceData = me.getEditorInfo(this, siteWide);

            if (instanceData == undefined) {
                return;
            }

            var newTextAreaId = 'rcm_ckedit_'+instanceData.pluginName+'_'+instanceData.instanceId+'_'+instanceData.textAreaId;
            //Create Text Area

            var newTextAres = $('<textarea id="'+newTextAreaId+'" >'+htmlToAddToTextArea+'</textarea>');

            $(this).html(newTextAres);

            var config = rcmCkConfig;

            config.bodyClass = instanceData.extraClases;

            newTextAres.ckeditor(config);
            var editor = $(newTextAres).ckeditorGet();
            /*
             editor.on('blur', function() {
             $("#hiddenEditor").ckeditorGet().focus();
             });
             */

            me.activeRichEditors.push({
                editor : newTextAres.ckeditorGet(),
                instanceId   : instanceData.instanceId,
                textId       : instanceData.textAreaId,
                pluginName   : instanceData.pluginName

            });

        });

        $('#RcmRealPage [data-textEdit]').each(function() {
            var instanceData = {};
            instanceData = me.getEditorInfo(this, siteWide);

            if (instanceData == undefined) {
                return;
            }

            $(this).attr('contentEditable',true).css('cursor','text');

            me.activeTextEditors.push({
                editor : this,
                instanceId   : instanceData.instanceId,
                textId       : instanceData.textAreaId,
                pluginName   : instanceData.pluginName

            });

        });

    };

    me.getEditorInfo = function(element, siteWide) {

        if (element == undefined) {
            return;
        }

        var dataReturn = {};

        //Get the ID value to use for the textarea
        dataReturn.textAreaId = $(element).attr('data-richEdit');

        if(dataReturn.textAreaId == undefined || dataReturn.textAreaId == '') {
            dataReturn.textAreaId = $(element).attr('data-textEdit');
        }

        if(dataReturn.textAreaId == undefined || dataReturn.textAreaId == '') {
            return;
        }


        pluginInfo = $(element).closest('.rcmPlugin');
        dataReturn.instanceId = $(pluginInfo).attr('data-rcmPluginInstanceId');
        dataReturn.pluginName = $(pluginInfo).attr('data-rcmPluginName');
        dataReturn.isSiteWide = $(pluginInfo).attr('data-rcmSiteWidePlugin');

        dataReturn.extraClases = $(this).attr('class');

        if (dataReturn.isSiteWide != null
            && dataReturn.isSiteWide=='Y'
            && siteWide == false
            ) {
            return;
        } else if ((dataReturn.isSiteWide == null || dataReturn.isSiteWide != 'Y')
            && siteWide == true
            ) {
            return;
        }

        return dataReturn;
    }

    me.disableNoEditRegions = function( /* siteWide */) {
        var siteWide = false;

        if (arguments.length == 1) {
            siteWide = arguments[0];
        }

        $("#RcmRealPage .rcmPlugin").each(function() {
            var isSiteWide = $(this).attr('data-rcmSiteWidePlugin');
            if ((siteWide === false && isSiteWide=='N')
                || (siteWide === true && isSiteWide=='Y')
                ) {
                return;
            }

            me.addOverlay(this);
            $(this).fadeTo(500, 0.2);
        });

    };

    me.addOverlay = function (element) {
        var divHeight = me.getElementHeight(element);
        var divWidth = me.getElementWidth(element);

        if (divHeight == 0) {
            return false;
        }

        var newDiv = $('<div style="position: absolute; top: 0; left: 0; z-index: 10000;"></div>');
        $(newDiv).height(divHeight);
        $(newDiv).width(divWidth);

        $(element).css('position', 'relative').append(newDiv);

    };

    me.getElementHeight = function(element) {
        var elementToUse = element;

        var loopCounter = 0;

        while($(elementToUse).height() == 0 && loopCounter < 10) {
            elementToUse = $(elementToUse).parent();
            loopCounter++;
        }

        return $(elementToUse).height();
    };

    me.getElementWidth = function(element) {
        var elementToUse = element;

        var loopCounter = 0;

        while($(elementToUse).width() == 0 && loopCounter < 10) {
            elementToUse = $(element).parent();
            loopCounter++;
        }

        return $(elementToUse).width();
    };



    me.addEditButtons = function() {

        $("#rcmAdminToolbarEdit").click(function(e){
            me.editButtonCallback(me);
            e.preventDefault();
        });
    };

    me.saveLayoutCallBack = function() {
        me.commonEditCallback();
        me.callPagePluginEditInitMethods();
        me.switchEditors();
        me.switchEditors(true);
        me.callSitePluginEditInitMethods();
        setTimeout(function () {me.saveLayoutSaveMethod();},1000);
    };

    me.saveLayoutSaveMethod = function() {
        me.saveAndPostData();
    };



    me.addLayoutEditor = function() {
        $(".showLayoutEditor").click(function(){
            me.showLayoutEditor();
        });
    };

    me.addLayoutPopOut = function() {
        $("#rcmLayoutMenuPopout").removeAttr('style');
        $("#rcmLayoutMenuPopout").click(function() {
            $("#layoutEditColumn").resizable("destroy");
            $("#layoutEditColumn").appendTo("body").css('position', 'fixed').css('top', 0).css('left', 0).height(300).zIndex(9999999999).draggable();
            $("#layoutEditColumn").show().resizable({
                handles: "all"
            });

            $("#rcmLayoutMenuPopout").css('background-position', '-242px -34px').click(function(){
                $("#layoutEditColumn").resizable("destroy");
                $("#layoutEditColumn").removeAttr('style').draggable("destroy").appendTo("#layoutEditContainer");
                $("#layoutEditColumn").show().resizable({
                    handles: "e"
                });


                me.addLayoutPopOut();

            })
        });
    }

    me.showLayoutEditor = function() {
        $("#rcmAdminToolbarSaveCancel").show();
        $("#rcmAdminToolbarEdit").hide();
        $(".saveButton").click(function(){me.saveLayoutCallBack()});
        $('html').addClass('rcmEditingPlugins');
        //Show Layout Editor
        $("#layoutEditColumn").show('slide').resizable({
            handles: "e"
        });

        me.addLayoutPopOut();

        //Add Menu Accordian
        $( "#rcmLayoutAccordion" ).accordion({ active: 22, collapsible: true });

//            $( "#RcmRealPage .rcmPlugin" ).resizable();

        $("#rcmLayoutAccordion .rcmPluginDrag").each(function(v, e){
            var selector = e;
            $(e).draggable({
                cursorAt : {left:40, top : 10},
                helper: function() {
                    var newDiv = $(this).find(".rcmPlugin");
                    var divWidth = me.getElementWidth(newDiv)
                    var helperDiv = $(newDiv).clone(false)
                    if (divWidth > 1000) {
                        $(helperDiv).width(350);
                    } else {
                        $(helperDiv).width(divWidth);
                    }



                    return $(helperDiv);
                },
                drag:function(){
                    // This is required for adding items to an empty
                    // sortable. the sortable "change" event handles
                    // everything else.
                    var placeHolder = $('.rcmPluginSortPlaceHolder');
                    //If placeholder exists and has not yet been filled with a plugin
                    if(placeHolder.length && !placeHolder.html().length){
                        me.updateDragPlaceHolder($(this).find(".rcmPlugin"));
                    }
                },
                revert: 'invalid',
                connectToSortable: '.rcmContainer',
                appendTo: 'body'

            });
        });



        $(".rcmContainer").each(function(v, e){
            var selector = e;
            $(e).sortable({
                items: '.rcmPlugin',
                connectWith: '.rcmContainer',
                dropOnEmpty: true,
                helper: "original",
                tolerance : 'pointer',
                placeholder: "rcmPluginSortPlaceHolder",
                forcePlaceholderSize: false,
                cursorAt : {left:40, top : 10},
                //cursor: "move", cursorAt: { top: 40, left: 40 },
                //                helper: function(event, ui) {
                //                    return $('<div>[icon]</div>');
                //                },
                change: function(event, ui) {
                    var placeHolder = $('.rcmPluginSortPlaceHolder');
                    if(placeHolder.length){
                        if(!placeHolder.html().length){
                            var pluginDiv;
                            if(ui.item.hasClass('rcmPluginDrag')){
                                //For adding new plugin
                                pluginDiv = $(ui.item).find(".rcmPlugin");
                            }else{
                                //For sorting existing
                                pluginDiv = ui.item;

                            }
                            placeHolder.attr(
                                'class',
                                pluginDiv.attr('class')
                                    + ' rcmPluginSortPlaceHolder'
                            )
                            placeHolder.html(pluginDiv.html());
                        }
                    }
                },
                receive: function(event, ui) {

                    var newItem = $(this).data().sortable.currentItem;
                    var initialInstance = $(ui.item).find(".initialState");
                    var newDiv = $(initialInstance).find(".rcmPlugin").clone(false);
                    var siteWide = $(newDiv).attr('data-rcmSiteWidePlugin');

                    if (siteWide != 'Y') {
                        $(newDiv).attr('data-rcmPluginInstanceId', me.getNewInstanceId());
                    }

                    if ($(initialInstance).is('.initialState')) {
                        $(newItem).replaceWith($(newDiv));


                        var siteWideName = $(newDiv).attr('data-rcmplugindisplayname').replace(/\s/g, '-');

                        if (siteWide == 'Y') {
                            $('#'+siteWideName).hide();
                        }
                    }
                },
                start: function(){
                    $('html').addClass('rcmDraggingPlugins');
                },
                stop: function (){
                    $('html').removeClass('rcmDraggingPlugins');
                }
            });
        });

//            $("#layoutEditColumn").droppable({
//                activeClass: 'active',
//                hoverClass:'hovered',
//                drop:function(event,ui){
//                    $(ui.draggable).remove();
//                }
//            });

        $( ".rcmContainer" ).disableSelection();

        //Add right click menu
        $.contextMenu({
            selector:'.rcmPlugin',


            //Here are the right click menu options
            items:{
                deleteMe:{
                    name:'Delete Plugin Instance',
                    icon:'delete',
                    callback:function (action, el, pos) {
                        var container = $(this);
                        var isSiteWide = $(container).attr('data-rcmSiteWidePlugin');
                        var siteWideName = $(container).attr('data-rcmPluginDisplayName').replace(/\s/g, '-');

                        if (isSiteWide == 'Y') {
                            $('#'+siteWideName).show();
                        }

                        $(this).remove();
                    }
                }
//                    separator3:"-"

            }
        });
    }


    me.updateDragPlaceHolder = function(pluginDiv){
        var placeHolder = $('.rcmPluginSortPlaceHolder');
        //If placeholder exists and has not yet been filled with a plugin
        if(placeHolder.length && !placeHolder.html().length){
            //Copy plugin css classes
            placeHolder.attr(
                'class',
                pluginDiv.attr('class')
                    + ' rcmPluginSortPlaceHolder'
            )
            //Copy plugin html
            placeHolder.html(pluginDiv.html());
        }
    }

    me.callPagePluginEditInitMethods = function() {
        me.callPlugins(me.pagePluginEdits, false);
    };

    me.callSitePluginEditInitMethods = function() {
        me.callPlugins(me.pagePluginEdits, true);
    };

    me.setPagePluginEdits = function(pagePluginEdits) {
        me.pagePluginEdits = pagePluginEdits;
    };

    me.callPlugins = function(pagePluginEdits, sitewide){

        $("#RcmRealPage .rcmPlugin").each(function(){
            var pluginName = $(this).attr('data-rcmPluginName');
            var siteWidePlugin = $(this).attr('data-rcmSiteWidePlugin');
            var instanceId = $(this).attr('data-rcmPluginInstanceId');

//            if (me.pagePluginEdits[pluginName] == undefined) {
//                return;
//            }

            if ((siteWidePlugin == 'N' && sitewide == true)
                || (siteWidePlugin == 'Y' && sitewide == false)
                ) {
                return;
            }

            var editClass = pluginName + 'Edit';

            if(typeof(window[editClass])=='function'){

                var plugin = new window[editClass](instanceId, $(this));
                plugin.initEdit();

                me.calledPlugins.push({
                    pluginObject : plugin,
                    instanceId   : instanceId,
                    pluginName   : pluginName
                });

            }else{
                return;
//                $().alert('Skipped enabling editing for ' +
//                    pluginName + '#' + instanceId +
//                    ' because the javascript class constructor "' +
//                    editClass + '()" could not be found.')
            }

        });

    };

    me.getStateFromCalledPlugins = function() {
        var dataToReturn = {};

        for(var index in me.calledPlugins) {
            if (!me.calledPlugins.hasOwnProperty(index)) {
                continue;
            }

            var instanceId = me.calledPlugins[index].instanceId;
            var pluginObject = me.calledPlugins[index].pluginObject;

            dataToReturn[instanceId] = {
                pluginData : {

                }
            };

            if ($.isFunction(pluginObject.getSaveData)) {
                dataToReturn[instanceId].pluginData.saveData= pluginObject.getSaveData();
            }

            if ($.isFunction(pluginObject.getAssets)) {
                dataToReturn[instanceId].pluginData.assets =  pluginObject.getAssets();
            }
        }

        return dataToReturn;
    };

    me.editButtonCallback = function(){
        $('html').addClass('rcmEditingPlugins');
        me.commonEditCallback();
        me.callPagePluginEditInitMethods();
        me.switchEditors();
        me.disableNoEditRegions();
    };

    me.cancelButtonCallback = function() {
        $("#rcmAdminToolbarSaveCancel").hide();
        $("#rcmAdminToolbarPleaseWait").show();
        location.reload();
    };

    me.attachEditSitePluginsClick = function() {
        $(".editSitePlugins a").click(function(){
            me.callSitePluginEditInitMethods();
            me.switchEditors(true);
            me.commonEditCallback();
            me.disableNoEditRegions(true);
        });
    };

    me.commonEditCallback = function() {

        me.disableLink($(".editSitePlugins a"));

        $("#RcmRealPage a").unbind('click').click(function(e){
            e.preventDefault();
        });

        $("#rcmAdminToolbarSaveCancel").show();
        $("#rcmAdminToolbarEdit").hide();

        $(".cancelButton").click(function(){
            me.cancelButtonCallback();
        });

        $(".saveButton").click(function(){
            me.saveAndPostData();
        });
    };

    me.saveAndPostData = function(){
        me.blockUI('Saving...');
        var pageMetaData = me.getPageMetaData();
        var ckEditorData = me.getCkEditsToSave();
        var textEditsData = me.getTextEditsToSave();

        var pluginSaveData = me.getStateFromCalledPlugins();
        var instancesData = me.getAllInstancesAndOrder();

        var dataToSave = $.extend(true, pageMetaData, ckEditorData, textEditsData, pluginSaveData, instancesData);

        var dataToSend = JSON.stringify(dataToSave);

        var input = $('<input type="hidden" ' +
            'name="saveData" value="" />').val(dataToSend);

        var form = $('<form method="post" action="/rcm-admin-save/' +
            this.page+'/'+this.language+'/'+this.pageRevision+'" name="rcmDataForm" id="rcmDataForm">').append(input);

        $("body").append(form);

        $("#rcmDataForm").submit();
    };

    me.getPageMetaData = function() {
        var dataToReturn = {
            main : {
                metaTitle : $("title").html(),
                metaDesc  : $('meta[name="description"]').attr('content'),
                metaKeyWords : $('meta[name="keywords"]').attr('content')
            }
        };

        return dataToReturn;
    };

    me.getCkEditsToSave = function(){
        var dataToReturn = {};

        for(var index in this.activeRichEditors) {

            if (!this.activeRichEditors.hasOwnProperty(index)) {
                continue;
            }

            if ($.isFunction(this.activeRichEditors[index].editor.getData)) {
                var instanceId = this.activeRichEditors[index].instanceId;
                var textId = this.activeRichEditors[index].textId;
                var saveData = this.activeRichEditors[index].editor.getData();

                if (dataToReturn[instanceId] == undefined) {
                    dataToReturn[instanceId] = {
                        pluginName : '',
                        pluginData : {
                            richEdits : {},
                            assets : []
                        }
                    };
                }

                dataToReturn[instanceId].pluginData.richEdits[textId] = saveData;
                dataToReturn[instanceId].pluginName = this.activeRichEditors[index].pluginName;

                //Record what assets this ckEdit is using
                var html=$('<div></div>');
                html.append(saveData);
                html.find('img').each(function(key, ele){
                    dataToReturn[instanceId].pluginData.assets.push(
                        $(ele).attr('src')
                    );
                });
                html.find('a').each(function(key, ele){
                    dataToReturn[instanceId].pluginData.assets.push(
                        $(ele).attr('href')
                    );
                });
                html.find('embed').each(function(key, ele){
                    dataToReturn[instanceId].pluginData.assets.push(
                        $(ele).attr('src')
                    );
                });
            }
        }

        return dataToReturn;
    };

    me.getTextEditsToSave = function(){
        var dataToReturn = {};

        for(var index in this.activeTextEditors) {

            if (!this.activeTextEditors.hasOwnProperty(index)) {
                continue;
            }

            var instanceId = this.activeTextEditors[index].instanceId;
            var textId = this.activeTextEditors[index].textId;
            var saveData = $.trim($(this.activeTextEditors[index].editor).html());
            var saveData = saveData.replace('\n', '');

            if (dataToReturn[instanceId] == undefined) {
                dataToReturn[instanceId] = {
                    pluginData : {
                        textEdits : {}
                    }
                };
            }

            dataToReturn[instanceId].pluginData.textEdits[textId] = saveData;
            dataToReturn[instanceId].pluginName = this.activeTextEditors[index].pluginName;
        }


        return dataToReturn;
    };

    me.getAllInstancesAndOrder = function() {
        var dataToReturn = {};

        $(".rcmContainer").each(function(){
            var containerNumber = $(this).attr('data-containerId');
            $(this).find(".rcmPlugin").each(function(index, value){
                var instanceId = $(value).attr('data-rcmPluginInstanceId');
                var pluginName = $(value).attr('data-rcmPluginName');
                dataToReturn[instanceId] = {
                    'container' : containerNumber,
                    'order' : index,
                    'pluginName' : pluginName
                }
            })
        });

        return dataToReturn;
    };

    /**
     * Sets the file manager
     *
     * @param {Object} fileManager
     */
    me.setFileManager = function(fileManager){
        me.fileManager = fileManager;
    };



    /**
     * Displays a file picker window
     *
     * @param {Function} callBack this is called when the user picks a file
     * @param {String} fileType optional file type to allow
     *
     * @return {Null}
     */
    me.showFileBrowser = function(callBack, fileType){

        //Declare a function for the file picker to call when user picks a file
        window.elFinderFileSelected = function(filePath){
            callBack(filePath);
        }

        //Open the file picker window
        var url=config.filebrowserBrowseUrl;
        if(fileType){
            url += '/' + fileType;
        }
        me.popup(url, config.filebrowserWindowWidth, config.filebrowserWindowHeight);
    }

    /**
     * Displays a file picker window that is connected to an input box.
     *
     * @param {Object} urlInputBox jQuery input box to attach to file URL
     * @param {String} fileType optional file type to allow
     *
     * @return {Null}
     */
    me.showFileBrowserForInputBox = function(urlInputBox, fileType){
        me.showFileBrowser(
            function(path){
                urlInputBox.attr('value', path);
                urlInputBox.trigger('change');
            },
            fileType
        )
    }

    /**
     * Opens Browser in a popup. The "width" and "height" parameters accept
     * numbers (pixels) or percent (of screen size) values.
     *
     * This is pulled from ckEditor code
     *
     * @param {String} url The url of the external file browser.
     * @param {String} width Popup window width.
     * @param {String} height Popup window height.
     * @param {String} options Popup window features.
     */
    me.popup = function( url, width, height, options )
    {
        width = width || '80%';
        height = height || '70%';

        if ( typeof width == 'string' && width.length > 1 && width.substr( width.length - 1, 1 ) == '%' )
            width = parseInt( window.screen.width * parseInt( width, 10 ) / 100, 10 );

        if ( typeof height == 'string' && height.length > 1 && height.substr( height.length - 1, 1 ) == '%' )
            height = parseInt( window.screen.height * parseInt( height, 10 ) / 100, 10 );

        if ( width < 640 )
            width = 640;

        if ( height < 420 )
            height = 420;

        var top = parseInt( ( window.screen.height - height ) / 2, 10 ),
            left = parseInt( ( window.screen.width  - width ) / 2, 10 );

        options = ( options || 'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes' ) +
            ',width='  + width +
            ',height=' + height +
            ',top='  + top +
            ',left=' + left;

        var popupWindow = window.open( '', null, options, true );

        // Blocked by a popup blocker.
        if ( !popupWindow )
            return false;

        try
        {
            // Chrome 18 is problematic, but it's not really needed here (#8855).
            var ua = navigator.userAgent.toLowerCase();
            if ( ua.indexOf( ' chrome/18' ) == -1 )
            {
                popupWindow.moveTo( left, top );
                popupWindow.resizeTo( width, height );
            }
            popupWindow.focus();
            popupWindow.location.href = url;
        }
        catch ( e )
        {
            popupWindow = window.open( url, null, options, true );
        }

        return true;
    }
}
