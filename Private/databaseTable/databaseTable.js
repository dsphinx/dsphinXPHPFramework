/**
 *  Copyright (c) 7/9/14 , dsphinx@plug.gr
 *  All rights reserved.
 *
 */


function databaseTableForm(formName) {
    $('#' + formName).submit();
}

function databaseTableFormRemove(formNamecontrol) {
    $('#' + formNamecontrol).val("True");
    $('#btnConfirmationDelete').dialog('open');
}



$(function () {

    $('#btnConfirmationDelete').dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        resizable: false,
        buttons: {
            "Confirm ": function () {
                $('form').submit();
            },
            "Cancel": function () {
                $(this).dialog("close");
            }
        }
    });


});