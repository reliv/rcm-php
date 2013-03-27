function rcmNewPageWizardCreatePage(dialogContainer) {
    var pageUrl = $("#rcmNewFromTemplateUrl").val();
    var pageName = $("#rcmNewFromTemplateName").val();
    var revision = $("#rcmPageRevision").val();
    var selectedLayout = null;
    var skipRedirect = $("#skipRedirect").val();

    if (revision < 0) {
        selectedLayout = $("#rcmNewPageSelectedLayout").val();
    }

    $.getJSON(
        '/rcm-admin-create-from-template/'+rcmEdit.language,
        {
            pageUrl: pageUrl,
            pageName: pageName,
            revision: revision,
            selectedLayout: selectedLayout
        },

        function(data) {
            var rcmNewFromTemplateErrorLine = $("#rcmNewFromTemplateErrorLine");
            if (data.dataOk == 'Y' && data.redirect) {

                if (skipRedirect && skipRedirect=='Y') {
                    $("#redirectUrl").val(data.redirect);
                    $(dialogContainer).dialog("close");
                } else {
                    window.location = data.redirect;
                }

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


$(".rcmNewPageLayoutContainer").click(function(){
    $(".rcmNewPageLinkOverlay").removeClass("rcmNewPageLinkOverlayActive");
    $(this).find(".rcmNewPageLinkOverlay").addClass("rcmNewPageLinkOverlayActive");
    var selectedValue = $(this).find(".rcmLayoutKeySelector").attr('name');
    $("#rcmNewPageSelectedLayout").val(selectedValue);
});

$("#rcmNewFromTemplateWizard").find("#rcmPageRevision").change(function(){
    var revision = $("#rcmPageRevision").val();

    if (revision < 0) {
        $("#rcmNewPageLayoutSelector").show();
    } else {
        $("#rcmNewPageLayoutSelector").hide();
    }
});

$('#rcmNewFromTemplateUrl').keyup(function(){
    var validationContainer = $("#rcmNewFromTemplateValidatorIndicator");
    rcmEdit.checkPageName(this, 'N', validationContainer);
});


$( "#rcmNewFromTemplateWizard").parent().dialog(
    "option",
    "buttons",
    [
        { text: "Ok", click: function() { rcmNewPageWizardCreatePage(this) }},
        { text: "Cancel", click: function() { $(this).dialog("close"); }}
    ]
);