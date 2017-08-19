<?php

    if (!$_SESSION['Auth']['UserName']) {
        echo Html::_error_light("Security", ' No priviledge !');

        return;
    }


    $_completed = isset($_POST['register_new_user_completed']) ? TRUE : FALSE;
    $_password  = (isset($_POST['password0']) && $_POST['password0'] === $_POST['password1']) ? TRUE : FALSE;

    if ($_completed) {


        if (!Auth::password_rules($_POST['password0'])) {
            echo Html::_error_light("Updating Error", Auth::$PASSWORD_MESSAGE);

            return;
        }

        if (!$_password) {
            echo Html::_error_light("Updating Error", ' Given Passwords does not match !');

            return;
        }


        include_once ('App/Library/Auth/Auth_Manager.php');

        // Auth_Manager::update_user($_POST);
        if (!Auth::changepassword($_POST['password0'], $_POST['password1'])) {
            echo Html::_error_light("Updating Password Error", '  Passwords does not changed, old one is valid !');
        } else {

            include('App/Library/Email/Email.php');


            if (!Gmail::Send(Auth::get_username(), Auth::get_username(), AppMessages::Show('Password_changed'), AppMessages::Show('Password_changed'))) { //mail($_SESSION['Registration_Key']['User'], 'Registration of ' . TITLE, $message, $headers)) {
                //   if (!mail(Auth::get_username(), ' You password is changed ! ', ' You password is changed ! '))
                echo Html::_error_light("Mail failed", '  fail to send email  !');
            }
        }

    }


    $age        = Auth::get_info("age_class");
    $sex        = Auth::get_info("sex");
    $profession = Auth::get_info("profession");
    $newsletter = (trim(Auth::get_info("newsletter")) == "Όχι, μην μου στέλνετε email") ? FALSE : TRUE;


    $account = $_SESSION['Auth']['Level'] > 0 ? " Χρήστη " : " Διαχειριστής ";

    include('details.html');



