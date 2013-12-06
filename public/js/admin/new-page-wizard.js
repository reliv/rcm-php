function rcmNewPageWizardCreatePage(dialogContainer) {
    var pageUrl = $("#rcmNewPageTemplateUrl").val();
    var pageName = $("#rcmNewPageTemplateName").val();
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
            var rcmNewPageTemplateErrorLine = $("#rcmNewPageTemplateErrorLine");
            if (data.dataOk == 'Y' && data.redirect) {

                if (skipRedirect && skipRedirect=='Y') {
                    $("#redirectUrl").val(data.redirect);
                    $(dialogContainer).dialog("close");
                } else {
                    window.location = data.redirect;
                }

            } else if(data.dataOk != 'Y' && data.error != '') {
                $(rcmNewPageTemplateErrorLine).html('<br /><p style="color: #FF0000;">'+data.error+'</p><br />').show();
            } else {
                $(rcmNewPageTemplateErrorLine).html('<br /><p style="color: #FF0000;">Communication Error!</p><br />').show();
            }
        }
    ).error(function(){
        $("#rcmNewPageTemplateErrorLine").html('<br /><p style="color: #FF0000;">Communication Error!</p><br />').show();
    })
}


$(".rcmNewPageLayoutContainer").click(function(){
    $(".rcmNewPageLinkOverlay").removeClass("rcmNewPageLinkOverlayActive");
    $(this).find(".rcmNewPageLinkOverlay").addClass("rcmNewPageLinkOverlayActive");
    var selectedValue = $(this).find(".rcmLayoutKeySelector").attr('name');
    $("#rcmNewPageSelectedLayout").val(selectedValue);
});

$("#rcmNewPageTemplateWizard").find("#rcmPageRevision").change(function(){
    var revision = $("#rcmPageRevision").val();

    if (revision < 0) {
        $("#rcmNewPageLayoutSelector").show();
    } else {
        $("#rcmNewPageLayoutSelector").hide();
    }
});

$('#rcmNewPageTemplateUrl').keyup(function(){
    var validationContainer = $("#rcmNewPageTemplateValidatorIndicator");
    rcmEdit.checkPageName(this, 'N', validationContainer);
});


$( "#rcmNewPageTemplateWizard").parent().dialog(
    "option",
    "buttons",
    [
        { text: "Ok", click: function() { rcmNewPageWizardCreatePage(this) }},
        { text: "Cancel", click: function() { $(this).dialog("close"); }}
    ]
);