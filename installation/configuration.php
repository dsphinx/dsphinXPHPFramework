<?php
/**
 *  Copyright (c) 2013-2015, dsphinx@plug.gr
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
 *      This product includes software developed by the dsphinx@plug.gr.
 *   4. Neither the name of the dsphinx@plug.gr nor the
 *      names of its contributors may be used to endorse or promote products
 *     derived from this software without specific prior written permission.
 *
 *  THIS SOFTWARE IS PROVIDED BY dsphinx@plug.gr ''AS IS'' AND ANY
 *  EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 *  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *  DISCLAIMED. IN NO EVENT SHALL dsphinx@plug.gr BE LIABLE FOR ANY
 *  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 *  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 *  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 *  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *
 */

DEFINE ( "RUNNING_MODE", "sandbox" );


DEFINE ( "APP_Version", "2.0" );
DEFINE ( "COPYRIGHT", 'dsphinx 2012-2015' ); //©
DEFINE ( "AUTHOR", " dsphinX ,  " );
DEFINE ( "AUTHOR_FOAF", "Media/semantic/dsphinx.foaf" );
DEFINE ( "DESCRIPTION", "[@DESCRIPTION]" );
DEFINE ( "KEYWORDS", "[@KEYWORDS]" );
DEFINE ( "TITLE", "[@TITLE]" );
DEFINE ( "SEMANTIC_WEB", TRUE );

setlocale(LC_ALL, 'el_GR.UTF8');
date_default_timezone_set('Europe/Athens');

$_path=dirname(__FILE__);
$_path=substr($_path, 0, -10); // App/config


if ( RUNNING_MODE == "sandbox" ) { //   Development phase - sandbox

	DEFINE ( "SQL_HOST", "[@SQL_HOST]" );
	DEFINE ( "SQL_BDD", "[@SQL_BDD]" );
	DEFINE ( "SQL_USER", "[@SQL_USER]" );
	DEFINE ( "SQL_PASSWORD", '[@SQL_PASSWORD]' );
	DEFINE ( "EMAIL_HOST", '[@EMAIL_HOST' );
	DEFINE ( "EMAIL_CONTACT", '[@EMAIL_CONTACT]' );

	DEFINE ( "GMAIL_EMAIL_SENDER", '[@GMAIL_EMAIL_SENDER]' );
	DEFINE ( "GMAIL_EMAIL_SENDER_PASS", '[@GMAIL_EMAIL_SENDER_PASS]' );
	DEFINE ( "HTML_READABLE", FALSE );

 	DEFINE ( "ORDERING_HOST_MAIL", EMAIL_HOST );
 	DEFINE ( "ORDERING_MAIL", '[@ORDERING_MAIL]' );

	error_reporting(E_ALL);

} else { // PRODUCTION SETTINGS - INTERNET  USAGE -- WEB HOSTING SETTINGS

	DEFINE ( "SQL_HOST", "[@SQL_HOST_PRODUCTION]" );
	DEFINE ( "SQL_BDD", "[@SQL_BDD_PRODUCTION]" );
	DEFINE ( "SQL_USER", "[@SQL_USER_PRODUCTION]" );
	DEFINE ( "SQL_PASSWORD", '[@SQL_PASSWORD_PRODUCTION]' );
	DEFINE ( "EMAIL_HOST", '[@EMAIL_HOST_PRODUCTION' );
	DEFINE ( "EMAIL_CONTACT", '[@EMAIL_CONTACT_PRODUCTION]' );

	DEFINE ( "GMAIL_EMAIL_SENDER", '[@GMAIL_EMAIL_SENDER_PRODUCTION]' );
	DEFINE ( "GMAIL_EMAIL_SENDER_PASS", '[@GMAIL_EMAIL_SENDER_PASS_PRODUCTION]' );
	DEFINE ( "REAL_BASE_DIR", '[@REAL_BASE_DIR]' ); //  /gnu/www/...

	DEFINE ( "ORDERING_HOST_MAIL", EMAIL_HOST );
	DEFINE ( "ORDERING_MAIL", '[@ORDERING_MAIL_PRODUCTION]' );


	error_reporting(0);
}


/*
 *          Session Management   Simple
 *
 *           need secure ?
 */
if ( !defined('__AJAX') ) {
	require_once( $_path . 'App/Library/Sessions/Sessions.php' );
	//require_once( _path . 'App/Library/Sessions/SessionsSecure.php');
}

/*
 *          Paths to include
 */
$_SESSION['PATHS']=array("__ROOT__"         =>$_path,
                         "CONFIG"           =>$_path . "App/cfg/config.php",
                         "LOGGING_ERRORS_IP"=>FALSE,
                         //  Logger::In
                         //  TRUE   αργεί λίγο
                         "JAVASCRIPT"       =>"App/Javascript/",
                         "LIBRARIES"        =>$_path . "App/Library/",
                         "UPLOADS"          =>"Media/uploads/",
                         "IMAGES"           =>"Media/images/",
                         "TEMPLATE_DIR"     =>"App/Templates/",
                         "TOOLS"            =>"App/Tools/",
                         "LANGUAGE_PATH"    =>$_path . "App/Locales/",
                         "FILES"            =>array( // Loading pages from ...
                                                     "HTML"    =>"Contents/",
                                                     "MEMBERS" =>"Private/",
                                                     // MEMBERS - OnLY Register User
                                                     'EXAMPLES'=>"installation/examplesCalls/",
                         ),
                         "MODULES"          =>array( //  Module
                                                     "SET"=>"App/Modules/",
                         ),
                         "SOCIAL_HEADER"    =>array( // Social Meta Info Tags on HTML Header
                                                     "TITLE"      =>TITLE,
                                                     "DESCRIPTION"=>DESCRIPTION,
                                                     "URL"        =>EMAIL_HOST,
                                                     "IMG"        =>EMAIL_HOST . "/Media/images/logoTag.png",
                                                     "FB_APP_ID"  =>"331302470294611",
                         ),
                         "FRAMEWORK"        =>array( // FRAMEWORK
                                                     "CONTROLLER"=>NULL,
                         ),
                         "LAST_URL"         =>NULL,
);


$_SESSION['LANGUAGE']     =isset( $_COOKIE['LANGUAGE'] ) ? $_COOKIE['LANGUAGE'] : 'EN';
$_SESSION['LANGUAGE_INFO']=$_SESSION['PATHS']['LANGUAGE_PATH'] . $_SESSION['LANGUAGE'] . ".site.php";


/**
 *
 *  Previous Ajax call from meta tags
 */
if ( isset( $_SESSION ['SOCIAL_HEADER']['TITLE'] ) ) {
	$_SESSION['PATHS']['SOCIAL_HEADER']['TITLE']      =$_SESSION ['SOCIAL_HEADER']['TITLE'];
	$_SESSION['PATHS']['SOCIAL_HEADER']['DESCRIPTION']=$_SESSION ['SOCIAL_HEADER']['DESCRIPTION'];
	$_SESSION['PATHS']['SOCIAL_HEADER']['URL']        =$_SESSION ['SOCIAL_HEADER']['URL'];
	$_SESSION['PATHS']['SOCIAL_HEADER']['IMG']        =$_SESSION ['SOCIAL_HEADER']['IMG'];

	unset( $_SESSION ['SOCIAL_HEADER'] );
}


if ( PHP_SAPI === "cli" ) { //if (!isset($_SERVER['HTTP_HOST'])) {
	trigger_error(' Executing Tests , PHPUnit ');
	//die('This script cannot be run from the CLI. Run it from a browser.');
}

unset( $_path );