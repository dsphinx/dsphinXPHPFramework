$(document).ready(function () {

    $('#forgotpw').click(function () {
        $('#form-signin').slideUp('slow', function () {
            $('#frmZForgot').slideDown("slow");
        });
    });
    $('#backtologin').click(function () {
        $('#frmZForgot').slideUp('slow', function () {
            $('#form-signin').slideDown("slow");
        });
    });
    $('#backtologin2').click(function () {
        $('#frmZConfirm').slideUp('slow', function () {
            $('#form-signin').slideDown("slow");
        });
    });

    $(window).load(function () {
        $("#loginwrapper").fadeIn();
    });

    $('.mainusers').html('');
    $('title').html("Είσοδος");


    $("#form-signin").submit(function (e) {

        var name = $('#user').val();
        var name2 = $('#pass').val();
        var calls = $('#_call_form').val();
        var salt = CryptoJS.lib.WordArray.random(128 / 8);
        var key256Bits500Iterations = CryptoJS.PBKDF2(calls, salt, {keySize: 256 / 32, iterations: 500});
        var iv = CryptoJS.enc.Hex.parse(calls);

        var encrypted = CryptoJS.AES.encrypt(name2, key256Bits500Iterations, {iv: iv});
        var data_base64 = encrypted.ciphertext.toString(CryptoJS.enc.Base64);
        var iv_base64 = encrypted.iv.toString(CryptoJS.enc.Base64);
        var key_base64 = encrypted.key.toString(CryptoJS.enc.Base64);
        var rememberME = 0 ;

        if ($('#remember').is(":checked"))
        {
            rememberME = 1;
        }



        if (name2.length < 8) {
            $('#msgbox').html('Password > 7 ').addClass('label label-danger').fadeTo(900, .9,
                function () {
                    $(this).hide();
                });
            e.preventDefault();
            return;
        }

        $("#msgbox").removeClass().addClass('label label-info').text(' authenticating ').fadeIn(1000);


        $.postJSON('App/Api/Auth.php', {
                user: name,
                pass: data_base64,
                call_service: 1,
                iv2: calls,
                iv: iv_base64,
                key: key_base64,
                rememder: rememberME

            }, function (data, status, xhr) {

                if (status == 'success') {
                    if (data.Auth === 'FALSE') {
                        $("#msgbox").fadeTo(200, 0.1, function () {
                            $(this).html(data.Result).addClass('label label-danger').fadeTo(900, .9,
                                function () {
                                    $(this).hide();
                                    $('#user').val('');
                                    $('#pass').val('');
                                });
                        });
                    }
                    if (data.Auth === 'TRUE') {
                        $("#msgbox").fadeTo(200, 0.1, function () {
                            $(this).html(' entering ... ').addClass('label label-info').fadeTo(900, 1,
                                function () {
                                    document.location = data.URL;
                                });
                        });
                    }
                }
                else {

                    $("#msgbox").fadeTo(200, 0.1, function () {
                        $(this).html('Failed to Connect!').addClass('label label-danger').fadeTo(900, .9,
                            function () {
                                $(this).hide();
                                $('#user').val('');
                                $('#pass').val('');
                            });
                    });
                }
            }
        );

        return false;
    });
});


function geoLocationON(position) {
    var geo = position.coords.latitude + "," + position.coords.longitude;
    var field = document.getElementById("connectedCoordinates");

    if (field) {
        field = geo;
        //  console.log(geo);
    }
}

function geoLocationOFF(error) {
    // console.log(" decline ");
}


if (navigator.geolocation) {
    var options = {enableHighAccuracy: true, timeout: 850};
    navigator.geolocation.getCurrentPosition(geoLocationON, geoLocationOFF, options);
}

