/**
 * DirectiveHtmlEditorToolbar
 * @param rcmHtmlEditorService
 * @constructor
 */
RcmHtmlEditorToolbar = function (rcmHtmlEditorService) {

    var self = this;

    var loadSkin = function (skin, loadedCallback, errorCallback) {
        var skinUrl = tinymce.baseURL + '/skins/' + skin;

        var skinUiCss = skinUrl + '/skin.min.css';

        // Load content.min.css or content.inline.min.css + (editor.inline ? '.inline' : '')
        //editor.contentCSS.push(skinUrl + '/content' + '.min.css');
        tinymce.DOM.styleSheetLoader.load(
            skinUiCss,
            loadedCallback,
            errorCallback
        );
    };

    var link = '';

    // * DEBUG TEMPLATE //self.templateUrl = '/modules/rcm/html-editor/adapter-tinymce/html/toolbar-template-debug.html;
    self.template = '<div class="htmlEditorToolbar" ng-cloak><div class="loading" ng-show="rcmHtmlEditorService.toolbarLoading">Loading...</div><div ng-hide="rcmHtmlEditorService.toolbarLoading"><div class="mce-fake" ng-show="(rcmHtmlEditorService.showFixedToolbar || !rcmHtmlEditorService.fixedToolbarToggle) && !rcmHtmlEditorService.isEditing"><div class="mce-tinymce mce-tinymce-inline mce-container mce-panel" role="presentation"><div class="mce-container-body mce-abs-layout"><div class="mce-toolbar-grp mce-container mce-panel mce-first mce-last"><div class="mce-container-body mce-stack-layout"><div class="mce-container mce-toolbar mce-first mce-last mce-stack-layout-item"><div class="mce-container-body mce-flow-layout"><div id="mcefake_33" class="mce-container mce-first mce-flow-layout-item mce-btn-group" role="group"><div id="mcefake_33-body"><div id="mcefake_0" class="mce-widget mce-btn mce-disabled mce-first mce-last" tabindex="-1" aria-labelledby="mcefake_0" role="button" aria-label="Source code"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-code"></i></button></div></div></div><div id="mcefake_34" class="mce-container mce-flow-layout-item mce-btn-group" role="group"><div id="mcefake_34-body"><div id="mcefake_1" class="mce-widget mce-btn mce-disabled mce-first" tabindex="-1" aria-labelledby="mcefake_1" role="button" aria-label="Undo" aria-disabled="true"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-undo"></i></button></div><div id="mcefake_2" class="mce-widget mce-btn mce-disabled mce-last" tabindex="-1" aria-labelledby="mcefake_2" role="button" aria-label="Redo" aria-disabled="true"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-redo"></i></button></div></div></div><div id="mcefake_35" class="mce-container mce-flow-layout-item mce-btn-group" role="group"><div id="mcefake_35-body"><div id="mcefake_3" class="mce-widget mce-btn mce-disabled mce-menubtn mce-first mce-last" tabindex="-1" aria-labelledby="mcefake_3" role="button" aria-haspopup="true"><button id="mcefake_3-open" role="presentation" type="button" tabindex="-1"><span>Formats</span> <i class="mce-caret"></i></button></div></div></div><div id="mcefake_36" class="mce-container mce-flow-layout-item mce-btn-group" role="group"><div id="mcefake_36-body"><div id="mcefake_4" class="mce-widget mce-btn mce-disabled mce-colorbutton mce-first mce-last" role="button" tabindex="-1" aria-haspopup="true" aria-label="Text color"><button role="presentation" hidefocus="1" type="button" tabindex="-1"><i class="mce-ico mce-i-forecolor"></i> <span id="mcefake_4-preview" class=mce-preview></span></button> <button type="button" class=mce-open hidefocus="1" tabindex="-1"><i class=mce-caret></i></button></div></div></div><div id="mcefake_37" class="mce-container mce-flow-layout-item mce-btn-group" role="group"><div id="mcefake_37-body"><div id="mcefake_5" class="mce-widget mce-btn mce-disabled mce-first" tabindex="-1" aria-labelledby="mcefake_5" role="button" aria-label="Bold" aria-pressed=true><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-bold"></i></button></div><div id="mcefake_6" class="mce-widget mce-btn mce-disabled" tabindex="-1" aria-labelledby="mcefake_6" role="button" aria-label="Italic"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-italic"></i></button></div><div id="mcefake_7" class="mce-widget mce-btn mce-disabled" tabindex="-1" aria-labelledby="mcefake_7" role="button" aria-label="Underline"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-underline"></i></button></div><div id="mcefake_8" class="mce-widget mce-btn mce-disabled" tabindex="-1" aria-labelledby="mcefake_8" role="button" aria-label="Strikethrough"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-strikethrough"></i></button></div><div id="mcefake_9" class="mce-widget mce-btn mce-disabled" tabindex="-1" aria-labelledby="mcefake_9" role="button" aria-label="Subscript"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-subscript"></i></button></div><div id="mcefake_10" class="mce-widget mce-btn mce-disabled" tabindex="-1" aria-labelledby="mcefake_10" role="button" aria-label="Superscript"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-superscript"></i></button></div><div id="mcefake_11" class="mce-widget mce-btn mce-disabled mce-last" tabindex="-1" aria-labelledby="mcefake_11" role="button" aria-label="Clear formatting"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-removeformat"></i></button></div></div></div><div id="mcefake_38" class="mce-container mce-flow-layout-item mce-btn-group" role="group"><div id="mcefake_38-body"><div id="mcefake_12" class="mce-widget mce-btn mce-disabled mce-first" tabindex="-1" aria-labelledby="mcefake_12" role="button" aria-label="Align left"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-alignleft"></i></button></div><div id="mcefake_13" class="mce-widget mce-btn mce-disabled" tabindex="-1" aria-labelledby="mcefake_13" role="button" aria-label="Align center"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-aligncenter"></i></button></div><div id="mcefake_14" class="mce-widget mce-btn mce-disabled" tabindex="-1" aria-labelledby="mcefake_14" role="button" aria-label="Align right"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-alignright"></i></button></div><div id="mcefake_15" class="mce-widget mce-btn mce-disabled mce-last" tabindex="-1" aria-labelledby="mcefake_15" role="button" aria-label="Justify"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-alignjustify"></i></button></div></div></div><div id="mcefake_39" class="mce-container mce-flow-layout-item mce-btn-group" role="group"><div id="mcefake_39-body"><div id="mcefake_16" class="mce-widget mce-btn mce-disabled mce-first" tabindex="-1" aria-labelledby="mcefake_16" role="button" aria-label="Bullet list"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-bullist"></i></button></div><div id="mcefake_17" class="mce-widget mce-btn mce-disabled" tabindex="-1" aria-labelledby="mcefake_17" role="button" aria-label="Numbered list"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-numlist"></i></button></div><div id="mcefake_18" class="mce-widget mce-btn mce-disabled" tabindex="-1" aria-labelledby="mcefake_18" role="button" aria-label="Decrease indent"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-outdent"></i></button></div><div id="mcefake_19" class="mce-widget mce-btn mce-disabled mce-last" tabindex="-1" aria-labelledby="mcefake_19" role="button" aria-label="Increase indent"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-indent"></i></button></div></div></div><div id="mcefake_40" class="mce-container mce-flow-layout-item mce-btn-group" role="group"><div id="mcefake_40-body"><div id="mcefake_20" class="mce-widget mce-btn mce-disabled mce-first" tabindex="-1" aria-labelledby="mcefake_20" role="button" aria-label="Cut"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-cut"></i></button></div><div id="mcefake_21" class="mce-widget mce-btn mce-disabled" tabindex="-1" aria-labelledby="mcefake_21" role="button" aria-label="Copy"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-copy"></i></button></div><div id="mcefake_22" class="mce-widget mce-btn mce-disabled mce-last" tabindex="-1" aria-labelledby="mcefake_22" role="button" aria-pressed=false aria-label="Paste as text"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-pastetext"></i></button></div></div></div><div id=mcefake_41 class="mce-container mce-flow-layout-item mce-btn-group" role="group"><div id="mcefake_41-body"><div id="mcefake_23" class="mce-widget mce-btn mce-disabled mce-first" tabindex="-1" aria-labelledby="mcefake_23" role="button" aria-label="Insert/edit image"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-image"></i></button></div><div id="mcefake_24" class="mce-widget mce-btn mce-disabled mce-menubtn" tabindex="-1" aria-labelledby="mcefake_24" role="button" aria-label="Table" aria-haspopup="true"><button id="mcefake_24-open" role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-table"></i> <span></span> <i class=mce-caret></i></button></div><div id="mcefake_25" class="mce-widget mce-btn mce-disabled" tabindex="-1" aria-labelledby="mcefake_25" role="button" aria-label="Horizontal line"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-hr"></i></button></div><div id="mcefake_26" class="mce-widget mce-btn mce-disabled mce-last" tabindex="-1" aria-labelledby="mcefake_26" role="button" aria-label="Special character"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-charmap"></i></button></div></div></div><div id="mcefake_42" class="mce-container mce-last mce-flow-layout-item mce-btn-group" role="group"><div id="mcefake_42-body"><div id="mcefake_27" class="mce-widget mce-btn mce-disabled mce-first" tabindex="-1" aria-labelledby="mcefake_27" role="button" aria-label="Insert/edit link"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-link"></i></button></div><div id="mcefake_28" class="mce-widget mce-btn mce-disabled" tabindex="-1" aria-labelledby="mcefake_28" role="button" aria-label="Remove link"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-unlink"></i></button></div><div id="mcefake_29" class="mce-widget mce-btn mce-disabled mce-last" tabindex="-1" aria-labelledby="mcefake_29" role="button" aria-label="Anchor"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-anchor"></i></button></div></div></div><div id="mcefake_42" class="mce-container mce-last mce-flow-layout-item mce-btn-group" role="group"><div id="mcefake_42-body"><div id="mcefake_27" class="mce-widget mce-btn mce-disabled mce-first mce-last" tabindex="-1" aria-labelledby="mcefake_27" role="button" aria-label="Insert/edit link"><button role="presentation" type="button" tabindex="-1"><i class="mce-ico mce-i-removeformat"></i></button></div></div></div></div></div></div></div></div></div></div><div id="externalToolbarWrapper"></div></div></div>';

    self.compile = function (tElm, tAttr) {

        rcmHtmlEditorService.fixedToolbarToggle = (tAttr.htmlEditorToolbarToggle == 'true');

        // fixedToolbarToggle requires TinyMCE CSS to be loaded on the page or it will not be displayed correctly
        if (!rcmHtmlEditorService.fixedToolbarToggle) {

            var skin = (tAttr.htmlEditorToolbarDefaultSkin) ? tAttr.htmlEditorToolbarDefaultSkin : 'lightgray';

            rcmHtmlEditorService.fixedToolbarDefaultSkin = skin;

            var originalStyle = tElm.attr('style');

            if (typeof originalStyle === 'undefined') {
                originalStyle = '';
            }

            tElm.attr('style', 'display: none;');

            loadSkin(
                rcmHtmlEditorService.fixedToolbarDefaultSkin,
                function () {
                    tElm.attr('style', originalStyle);
                },
                function () {
                    tElm.attr('style', originalStyle);
                }
            );
        }

        return function (scope, element, attrs, htmlEditorState) {

            scope.rcmHtmlEditorService = rcmHtmlEditorService;
        }
    };

    /**
     *
     * @returns {{compile: Function, template: string}}
     */
    self.getDirective = function(){
        return {
            compile: self.compile,
            template: self.template
        }
    };

};
