function rcmNewSiteWizardCreateSite(dialogContainer) {
    $.getJSON(
        '/rcm-admin-create-site/create/'+rcmEdit.language,
        {
            domain : $("#rcmSiteWizardDomain").val(),
            country : $("#rcmSiteWizardCountry").val(),
            language : $("#rcmSiteWizardLanguage").val(),
            siteToClone : $("#rcmSiteWizardCloneSite").val()
        },

        function(data) {
            var rcmNewFromTemplateErrorLine = $("#rcmNewSiteErrorLine");
            if (data.dataOk == 'Y' && data.redirect) {
                    window.location = data.redirect;
            } else if(data.dataOk != 'Y' && data.error != '') {
                $(rcmNewFromTemplateErrorLine).html('<br /><p style="color: #FF0000;">'+data.error+'</p><br />').show();
            } else {
                $(rcmNewFromTemplateErrorLine).html('<br /><p style="color: #FF0000;">Communication Error!</p><br />').show();
            }
        }
    ).error(function(){
        $("#rcmNewFromTemplateErrorLine").html('<br /><p style="color: #FF0000;">Communication Error!</p><br />').show();
    })
}

$(function(){
    $("#rcmSiteWizardDomain").dialogIn(
        'textWithAjaxValidator',
        'Domain',
        '',
        '/rcm-admin-check-domain/'+rcmEdit.language,
        false
    );
});



$( "#rcmNewFromTemplateWizard").parent().dialog(
    "option",
    "buttons",
    [
        { text: "Ok", click: function() { rcmNewSiteWizardCreateSite(this) }},
        { text: "Cancel", click: function() { $(this).dialog("close"); }}
    ]
);