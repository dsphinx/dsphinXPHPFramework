<?php

/*
 *   Role Based Auth Policy
 */
//if ( Controller::Policy(1, " Admin Pages") ) {  	return; }

if ( Controller::PolicyNOTAllowedActionExcept("sendmail") ) { return; }


$db = MyDB::db();
$cmd = Controller::param("cmd");
$id = Controller::param_integer("id");


switch ( $cmd ) {
	case "templates":
		include( 'templates.php' );
		break;
	case "theme":
		include( 'theme.php' );
		break;

	case "userlogs":
		include( 'userlogs.php' );
		break;
	case "logs":
		include( 'logs.php' );
		break;
	case "admins":
		include( 'Admins/Admins.php' );
		break;
	case "users":
		include( 'Userlist/Userlist.php' );
		break;
	default:
		if ( file_exists(__DIR__ . DIRECTORY_SEPARATOR . $cmd . ".php") ) {
			include __DIR__ . DIRECTORY_SEPARATOR . $cmd . ".php";

		}

		break;
}


// echo $cmd;
// echo " id = $id ";