<?php


Init::_load("Auth");

if (Input::In_GET_Url('cmd') == "logout") {
	Auth::logout();
	echo '<meta http-equiv="refresh" content="1;url=' . EMAIL_HOST . '">';
	Cookies::Delete('theme');

	return;

}

$infoLog = NULL;
if (Cookies::Get('AuthLastLogin')) {
	$infoLog = " <small> Last logged in at " . Cookies::Get('AuthLastLogin') . "</small>"; // ." , with <mark>".Cookies::Get('Auth')."</mark> account ! <br/> Welcome back ";
}


$_FORM_KEY = Html_Form::_set_call();



$FORM_KEY = $_FORM_KEY;
$INFO = $infoLog;
$TITLE = TITLE;


echo Html::javascript('aes.js');    //  Password not plain text
echo Html::javascript('pbkdf2.js');  //  Password not plain text

