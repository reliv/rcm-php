/**
 * rcmHtmlEditorGlobalConfig
 * @type {{language: string, baseUrl: string, fixed_toolbar_container: string}}
 */
var rcmHtmlEditorGlobalConfig = {

    language: 'en',
    baseUrl: "/", //"<?php echo $baseUrl; ?>";
    fixed_toolbar_container: '#externalToolbarWrapper'

};

/**
 * rcmHtmlEditorConfig
 * @type {{htmlEditorOptions: {defaults: {link_list: string, relative_urls: boolean, optionsName: string, force_br_newlines: boolean, force_p_newlines: boolean, forced_root_block: string, paste_as_text: boolean, inline: boolean, encoding: string, fixed_toolbar_container: (*|$scope.tinymceOptions.fixed_toolbar_container|string|settings.fixed_toolbar_container), language: *, menubar: boolean, plugins: string, document_base_url: (string|l.baseUrl|*|j.baseUrl|settings.baseUrl|baseUrl), statusbar: boolean, style_formats_merge: boolean, style_formats: {title: string, items: {title: string, selector: string, styles: {float: string, margin: string}}[]}[], image_advtab: boolean, toolbar: *[]}, text: {link_list: string, relative_urls: boolean, optionsName: string, force_br_newlines: boolean, force_p_newlines: boolean, forced_root_block: string, paste_as_text: boolean, inline: boolean, encoding: string, fixed_toolbar_container: (*|$scope.tinymceOptions.fixed_toolbar_container|string|settings.fixed_toolbar_container), language: *, menubar: boolean, plugins: string, document_base_url: (string|l.baseUrl|*|j.baseUrl|settings.baseUrl|baseUrl), statusbar: boolean, image_advtab: boolean, toolbar: *[]}, simpleText: {link_list: string, relative_urls: boolean, optionsName: string, force_br_newlines: boolean, force_p_newlines: boolean, forced_root_block: string, paste_as_text: boolean, inline: boolean, encoding: string, fixed_toolbar_container: (*|$scope.tinymceOptions.fixed_toolbar_container|string|settings.fixed_toolbar_container), language: *, menubar: boolean, plugins: string, document_base_url: (string|l.baseUrl|*|j.baseUrl|settings.baseUrl|baseUrl), statusbar: boolean, toolbar: *[]}}}}
 */
var rcmHtmlEditorConfig = {

    toolbar_container_prefix: '#htmlEditorToolbar-',

    htmlEditorOptions: {
        defaults: {
            link_list: "/rcm-page-search/title?format=tinyMceLinkList",
            relative_urls: false,
            optionsName: 'defaults',
            force_br_newlines: false,
            force_p_newlines: true,
            forced_root_block: '',
            paste_as_text: true,

            inline: true,
            encoding: "raw",
            fixed_toolbar_container: rcmHtmlEditorGlobalConfig.fixed_toolbar_container,
            language: rcmHtmlEditorGlobalConfig.language,

            menubar: false,
            plugins: "anchor, charmap, code, hr, image, linkwithjqueryautocomplete, paste, table, textcolor, colorpicker, rcmFileChooser, lists",
            external_plugins: {
                'linkwithjqueryautocomplete': '/vendor/rcm-tinymce/plugins/linkwithjqueryautocomplete/plugin.min.js',
                'rcmFileChooser': '/vendor/rcm-s3-file-chooser/rcm-file-chooser-tiny-mce-plugin/plugin.js'
            },
            document_base_url: rcmHtmlEditorGlobalConfig.baseUrl,
            statusbar: false,

            style_formats_merge: true,
            style_formats: [
                {
                    title: "Image",
                    items: [
                        {
                            title: 'Align Left',
                            selector: 'img',
                            styles: {
                                'float': 'left',
                                'margin': '0 1em .5em 0'
                            }
                        },
                        {
                            title: 'Align Right',
                            selector: 'img',
                            styles: {
                                'float': 'right',
                                'margin': '0 0 .5em 1em'
                            }
                        }
                    ]
                }
            ],

            image_advtab: true,

            toolbar: [
                "code | undo redo | styleselect | forecolor | " +
                "bold italic underline strikethrough subscript superscript removeformat | " +
                "alignleft aligncenter alignright alignjustify | " +
                "bullist numlist outdent indent | cut copy pastetext | " +
                "image table hr charmap | link unlink anchor | removeformat"
            ]
        },
        text: {
            link_list: "/rcm-page-search/title?format=tinyMceLinkList",
            relative_urls: false,
            optionsName: 'text',
            force_br_newlines: false,
            force_p_newlines: true,
            forced_root_block: '',
            paste_as_text: true,

            inline: true,
            encoding: "raw",
            fixed_toolbar_container: rcmHtmlEditorGlobalConfig.fixed_toolbar_container,
            language: rcmHtmlEditorGlobalConfig.language,

            menubar: false,
            plugins: "anchor, charmap, code, hr, image, linkwithjqueryautocomplete, paste, table, textcolor, colorpicker, rcmFileChooser",
            external_plugins: {
                'linkwithjqueryautocomplete': '/vendor/rcm-tinymce/plugins/linkwithjqueryautocomplete/plugin.min.js',
                'rcmFileChooser': '/vendor/rcm-s3-file-chooser/rcm-file-chooser-tiny-mce-plugin/plugin.js'
            },
            document_base_url: rcmHtmlEditorGlobalConfig.baseUrl,
            statusbar: false,

            image_advtab: true,

            toolbar: [
                "code | undo redo | forecolor | " +
                "bold italic underline strikethrough subscript superscript removeformat | " +
                "outdent indent | cut copy pastetext | " +
                "image charmap | link unlink anchor | removeformat"
            ]
        },
        simpleText: {
            link_list: "/rcm-page-search/title?format=tinyMceLinkList",
            relative_urls: false,
            optionsName: 'simpleText',
            force_br_newlines: false,
            force_p_newlines: true,
            forced_root_block: '',
            paste_as_text: true,

            inline: true,
            encoding: "raw",
            fixed_toolbar_container: rcmHtmlEditorGlobalConfig.fixed_toolbar_container,
            language: rcmHtmlEditorGlobalConfig.language,

            menubar: false,
            plugins: "anchor, charmap, code, hr, image, linkwithjqueryautocomplete, paste, table, rcmFileChooser",
            external_plugins: {
                'linkwithjqueryautocomplete': '/vendor/rcm-tinymce/plugins/linkwithjqueryautocomplete/plugin.min.js',
                'rcmFileChooser': '/vendor/rcm-s3-file-chooser/rcm-file-chooser-tiny-mce-plugin/plugin.js'
            },
            document_base_url: rcmHtmlEditorGlobalConfig.baseUrl,
            statusbar: false,

            toolbar: [
                "code | " +
                "bold italic underline strikethrough subscript superscript removeformat | " +
                "link unlink anchor | removeformat"
            ]
        }
    }
};
