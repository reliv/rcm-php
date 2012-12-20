var rcmConfig = {
    filebrowserBrowseUrl: '/elfinder',
    filebrowserWindowHeight : '400',
    filebrowserWindowWidth : null
};

var rcmCkConfig = {
    forcePasteAsPlainText : true,
    sharedSpaces : {
        top : 'ckEditortoolbar',
        bottom : 'ckEditorfooter'
    },
    extraPlugins: 'sharedspace',
    removePlugins : 'resize, floatingspace',
    skin : 'kama',
//    contentsCss: '/modules/reliv-guest-site/css/guestSiteFonts.css',
    autoGrow_onStartup : true,
    autoGrow_minHeight: '35',
    toolbar: [
        { name: 'document', items : [ 'Source','-','Templates' ] },
        { name: 'undoRedo', items : ['Undo','Redo'] },
        { name: 'clipboard', items : ['Cut','Copy','Paste','PasteText','PasteFromWord'] },
        { name: 'SpellCheck', items : [ 'SpellChecker', 'Scayt' ] },
        { name: 'styles', items : [ 'Format' ] },
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
            '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
        { name: 'insert', items : [ 'Image', 'Table','HorizontalRule','SpecialChar' ] },
        { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
    ],
    filebrowserBrowseUrl : '/elfinder/ckeditor',
    filebrowserImageBrowseUrl : '/elfinder/ckeditor/images',
    filebrowserWindowHeight : '400',
    filebrowserWindowWidth : null
};

var rcmTinyMceConfig = {
    script_url: '/modules/rcm/vendor/tinymce/jscripts/tiny_mce/tiny_mce.js',
    theme : "advanced",
    plugins : "pagebreak,style,layer,table,save,advhr,advimage," +
        "advlink,emotions,iespell,inlinepopups,insertdatetime," +
        "preview,media,searchreplace,print,contextmenu,paste," +
        "directionality,fullscreen,noneditable,visualchars," +
        "nonbreaking,xhtmlxtras,template,autoresize",

    // Theme options
    theme_advanced_buttons1 : "save,newdocument,|,bold,italic," +
        "underline,strikethrough,|,justifyleft,justifycenter," +
        "justifyright,justifyfull,styleselect,formatselect," +
        "fontselect,fontsizeselect",

    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|," +
        "search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|," +
        "undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|," +
        "insertdate,inserttime,preview,|,forecolor,backcolor",

    theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat," +
        "visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|," +
        "print,|,ltr,rtl,|,fullscreen",

    theme_advanced_buttons4 : "insertlayer,moveforward,movebackward," +
        "absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|," +
        "visualchars,nonbreaking,template,pagebreak",

    theme_advanced_toolbar_location : "external",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "none",
    theme_advanced_resizing : true,
    content_css : '/modules/reliv-application/css/guestsite/guestSiteFonts.css'
};

/* Aloha Config */

