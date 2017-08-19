<?php

    /**
     *
     *    Api - Ajax Calls
     *
     */

    require_once ('../Library/Ajax/Ajax.php');
    require_once ('../Library/Auth/Auth.php');
    require_once ('../Library/Cookies.php');
    require_once ('../Library/Html/Html_Form.php');

    $_return = array(
        'Auth'   => "FALSE",
        'URL'    => EMAIL_HOST . '?page=signin', // Return URL
        'Result' => "Incorrect Username"
    );

    $_call_form = isset($_SESSION['_call_form']) ? $_SESSION['_call_form'] : NULL;

    // Testing from home
    $allow_from_ip = (RUNNING_MODE == "sandbox") ? TRUE : FALSE;

    /**
     *
     *  FORM hidden value , to prevent externals posts
     *
     */
    if (!Html_Form::_is_normal_call($_call_form)) {
        trigger_error(" Attack ??  " . $_SERVER['REMOTE_ADDR']);
        Auth::Logging("XSS", "Auth Attack , no session _call_form, File:" . __FILE__);
        Ajax_Call::OUT($_return);

        return FALSE;
    }

    if ($results = file_get_contents("php://input")) {

        $results = json_decode($results, TRUE);


        $login    = Ajax_Call::IN($results['user']);
        $pass     = Ajax_Call::IN($results['pass']);
        $pass_iv  = Ajax_Call::IN($results['iv']);
        $pass_key = Ajax_Call::IN($results['key']);
        $iv       = Ajax_Call::IN($results['iv2']); // Key to encrypt AES
        //  from JS hidden html input __call_form compare with sessions __call_form



        /**
         *
         *  Securing ? session_id()
         *
         */
        $service = Ajax_Call::IN($results['call_service']);


        if (empty($login) || empty($pass) || ($_call_form != $iv)) {
            return FALSE;
        }

        /**
         *   Calls only from our domain
         *
         */
        if (Ajax_Call::is_Call_from_Host($allow_from_ip)) {

        //        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
           if (filter_var($login, FILTER_SANITIZE_STRING)) {

                if (Auth::validation($login, $pass, $pass_iv, $pass_key)) {
                    $_return['Auth'] = 'TRUE';
                    $_return['URL']  = 'login.php';

	                Auth::rememberMyLoginAtSession($login, Ajax_Call::IN($results['rememder']));

                }

            }
        } else {
			  trigger_error(__FILE__ . " not calling from HOST config ");

		}

        if ($service) {
            Ajax_Call::OUT($_return);
        } else {
            Ajax_Call::redir_after_calls($_return['URL']);
        }

    }