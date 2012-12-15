function rcmSubmitSaveAsTemplate() {
    var pageName = $("#rcmTemplateNameInput").val();
    var revisionId = rcmEdit.pageRevision;

    $.getJSON('/rcm-admin-save-as-template/'+rcmEdit.language,
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

$('#rcmTemplateNameInput').keyup(function(){
    var validationContainer = $("#newSaveTemplateIndicator");
    rcmEdit.checkPageName(this, validationContainer);
});

$( "#rcmAdminPagePopoutWindow" ).dialog(
    "option",
    "buttons",
    [
        { text: "Ok", click: function() { rcmSubmitSaveAsTemplate() }},
        { text: "Cancel", click: function() { $(this).dialog("close"); }}
    ]
);