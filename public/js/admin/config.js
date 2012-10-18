var rcmConfig = {
    filebrowserBrowseUrl: '/elfinder',
    filebrowserWindowHeight : '400',
    filebrowserWindowWidth : null
};

var rcmCkConfig = {

    sharedSpaces : {
        top : 'ckEditortoolbar',
        bottom : 'ckEditorfooter'
    },
    extraPlugins : 'autogrow',
    removePlugins : 'resize',
    skin : 'kama',
    contentsCss: '/modules/private-app/css/guestsite/guestSiteFonts.css',
    autoGrow_minHeight: '35',
    toolbar: [
        { name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
        { name: 'undoRedo', items : ['Undo','Redo'] },
        { name: 'clipboard', items : ['Cut','Copy','Paste','PasteText','PasteFromWord'] },
        { name: 'SpellCheck', items : [ 'SpellChecker', 'Scayt' ] },
        { name: 'insert', items : [ 'Image', 'Table','HorizontalRule','SpecialChar' ] },
        { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
        '/',
        { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
            '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
        { name: 'colors', items : [ 'TextColor','BGColor' ] },
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
    content_css : '/modules/private-app/css/guestsite/guestSiteFonts.css',
};

/* Aloha Config */

