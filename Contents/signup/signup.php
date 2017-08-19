<?php
/**
 *  Copyright (c) 2013, dsphinx@plug.gr
 *  All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without
 *  modification, are permitted provided that the following conditions are met:
 *   1. Redistributions of source code must retain the above copyright
 *      notice, this list of conditions and the following disclaimer.
 *   2. Redistributions in binary form must reproduce the above copyright
 *      notice, this list of conditions and the following disclaimer in the
 *      documentation and/or other materials provided with the distribution.
 *   3. All advertising materials mentioning features or use of this software
 *      must display the following acknowledgement:
 *      This product includes software developed by the dsphinx.
 *   4. Neither the name of the dsphinx nor the
 *      names of its contributors may be used to endorse or promote products
 *     derived from this software without specific prior written permission.
 *
 *  THIS SOFTWARE IS PROVIDED BY dsphinx ''AS IS'' AND ANY
 *  EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 *  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *  DISCLAIMED. IN NO EVENT SHALL dsphinx BE LIABLE FOR ANY
 *  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 *  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 *  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 *  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *
 */


/**
 *
 *
 *   Depend on formoid form generator
 *
 */

// echo Html::css('bootstrapForm.css');

include_once('App/Library/Auth/Auth.php');

$_posted = isset($_POST['register_new_user']) ? TRUE : FALSE;
$_completed = isset($_POST['register_new_user_completed']) ? TRUE : FALSE;
$_password = (isset($_POST['password0']) && $_POST['password0'] === $_POST['password1']) ? TRUE : FALSE;
$_registration = isset($_GET['key']) ? $_GET['key'] : FALSE;


if ($_completed && isset($_SESSION['Registration_Key']['Key'])) {


	include_once('App/Library/Auth/Auth_Manager.php');

	if (Auth_Manager::insert_user($_POST)) {

		echo Html::_info(" Registration Completed <br/>", "  account is active ! ");
		unset($_SESSION['Registration_Key']);
		unset($_SESSION);
		// echo Controller::refreshPage("login.php?=main");
		echo ' please, close your browser !!! and login to site , have fun !';
		Controller_files::$stopLoadingRelativesFileFromDirectory = TRUE;

	}

	return;
}


if ($_registration && isset($_SESSION['Registration_Key']['Key'])) {

	if ($_registration == $_SESSION['Registration_Key']['Key']) {

		$tmp = Controller::get_plain_file(__DIR__ . '/level/signup_stage2.html', 'html', TRUE);
		$tmp = str_replace("[@mail]", $_SESSION['Registration_Key']['User'], $tmp);
		$tmp = str_replace("[@email]", $_SESSION['Registration_Key']['User'], $tmp);
		$tmp = str_replace("[@passwd]", $_SESSION['Registration_Key']['Pass'], $tmp);
		$tmp = str_replace("[@login]", $_SESSION['Registration_Key']['login'], $tmp);
		$tmp = str_replace("[@name]", $_SESSION['Registration_Key']['name'], $tmp);
		$tmp = str_replace("[@title]", TITLE, $tmp);

		Controller_files::$stopLoadingRelativesFileFromDirectory = TRUE;

		echo $tmp;


	} else {

		echo Html::_error_light("Registration Error", ' Something wrong is happening, try again later !');
		unset($_SESSION['Registration_Key']);
	}

	return;
}


if ($_posted && !$_password) {

	echo Html::_error_light("Registration Error", ' Given Passwords does not match !');
	$_posted = FALSE;
}


if ($_posted) {

	$_SESSION['Registration_Key'] = array();
	$_SESSION['Registration_Key']['Key'] = md5($_SESSION['PATHS']['SOCIAL_HEADER']['DESCRIPTION'] . time());
	$_SESSION['Registration_Key']['User'] = Input::In('email');
	$_SESSION['Registration_Key']['Pass'] = Input::In('password0');
	$_SESSION['Registration_Key']['login'] = Input::In('login');
	$_SESSION['Registration_Key']['name'] = Input::In('name');

	if (!Auth::password_rules($_SESSION['Registration_Key']['Pass'])) {
		echo Html::_error_light("Registration Error - Password Rules", Auth::$PASSWORD_MESSAGE);

		return;
	}

	//echo 'Key is= ' . $_SESSION['Registration_Key']['Key'];
	$link = EMAIL_HOST . '?page=signup&key=' . $_SESSION['Registration_Key']['Key'];

	$headers = "Content-Type: text/html; charset=UTF-8\r\n";
	$message = '<html><body><h3> Registration </h3>
                       In order to complete your registration , please click <a href="' . $link . '"> here !!!! </a>
                       </body></html>';


	include('App/Library/Email/Email.php');


	if (!Gmail::Send($_SESSION['Registration_Key']['User'], $_SESSION['Registration_Key']['User'], AppMessages::Show('Register_subject'), $message)) { //mail($_SESSION['Registration_Key']['User'], 'Registration of ' . TITLE, $message, $headers)) {

		echo Html::_error_light("Registration Error", ' Failed to send confirmation email  !');
	}

	echo '<ul class="breadcrumb">
                   <li > ' . TITLE . ' </li>
                   <li class="active"> Confirmation   </li>
                </ul>';
	echo Html::_info(" Please check your email <br/>", " and confirm your registration ! ");
	Controller_files::$stopLoadingRelativesFileFromDirectory = TRUE;


} else {

	unset($_SESSION['Registration_Key']);
	//   include ('signup.html');
}


