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

/**
 *  Cookies
 */

DEFINE ( "APP_Version", "2.0" );
DEFINE ( "COPYRIGHT", ' ©   dsphinx 2012-2015' ); //©
DEFINE ( "AUTHOR", " dsphinX ,  " );
DEFINE ( "AUTHOR_FOAF", "Media/semantic/dsphinx.foaf" );
DEFINE ( "DESCRIPTION", " dsphinx framework tets " );
DEFINE ( "KEYWORDS", " dsphinx framework testing " );
DEFINE ( "TITLE", "  Framework II Testing" );
DEFINE ( "SEMANTIC_WEB", TRUE );

setlocale(LC_ALL, 'el_GR.UTF8');
date_default_timezone_set('Europe/Athens');

$_path=dirname(__FILE__);
$_path=substr($_path, 0, -10); // App/config


if ( RUNNING_MODE == "sandbox" ) { //   Development phase - sandbox

	//    DEFINE ("__AJAX", TRUE);  //   Do not use mysqli Session
	DEFINE ( "SQL_HOST", "127.0.0.1" );
	DEFINE ( "SQL_BDD", "Authentication" );
	DEFINE ( "SQL_USER", "tester" );
	DEFINE ( "SQL_PASSWORD", 'tester' );
	DEFINE ( "EMAIL_HOST", 'http://gnu/Framework/version_II/' );
	DEFINE ( "EMAIL_CONTACT", 'dsphinx@gmail.com' );

	DEFINE ( "GMAIL_EMAIL_SENDER", 'xxxx@gmail.com' );
	DEFINE ( "GMAIL_EMAIL_SENDER_PASS", 'xxxx' );
	DEFINE ( "HTML_READABLE", FALSE );

	DEFINE ( "ORDERING_HOST_MAIL", 'http://gnu/Framework/version_II/' );
	DEFINE ( "ORDERING_MAIL", 'dsphinx@me.com' );

	error_reporting(E_ALL);

} else { // INTERNET  USAGE -- WEB HOSTGINS
	DEFINE ( "SQL_BDD", "xxx" );
	DEFINE ( "SQL_USER", "xxxx" );

	DEFINE ( "SQL_PASSWORD", 'xxc' );
	DEFINE ( "REAL_BASE_DIR", '/mxxxx' );     // obselete

	DEFINE ( "ORDERING_HOST_MAIL", 'http://uri/' );
	DEFINE ( "ORDERING_MAIL", 'xxxxx' );
	DEFINE ( "EMAIL_HOST", 'http://www.xxx.com/' );
	DEFINE ( "EMAIL_CONTACT", 'office@xxx.com' );

	DEFINE ( "GMAIL_EMAIL_SENDER", 'xx.com' );
	DEFINE ( "GMAIL_EMAIL_SENDER_PASS", 'xxx' );
	error_reporting(0); // Set E_ALL for debuging

}


/*
 *          Session Management   Simple
 *
 *           need secure ?
 *
 *
 *
 */
if ( !defined('__AJAX') ) {
	require_once( $_path . 'App/Library/Sessions/Sessions.php' );
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
                                                     "HTML"                 =>"Contents/",
                                                     "MEMBERS"              =>"Private/",
                                                     // MEMBERS - OnLY Register User
                                                     //"MEMBERS"               => "Private/projectDiagnosis", // MEMBERS - OnLY Register User
                                                     "CONTENTS"             =>"Pages/",
                                                     "DEVELOPER"            =>"Developer/",
                                                     'TESTING_PLUGINS'      =>"Developer.Plugins/Testing_Plugins_Only/",
                                                     'TESTING_DESIGN_THEMES'=>"Developer.Plugins/Testing_Themes/",
                                                     'EXAMPLES_TESTING'     =>"installation/examplesCalls/",
                                                     'SCH'                  =>"SitesCode/sch/",
                                                     'ONOMAZWAFYTA'         =>"SitesCode/OnomaZwaFyta/",
                         ),
                         "MODULES"          =>array( //  Module
                                                     "SET"=>"App/Modules/",

                         ),
                         "SOCIAL_HEADER"    =>array( // Social Meta Info Tags on HTML Header
                                                     "TITLE"      =>TITLE,
                                                     "DESCRIPTION"=>DESCRIPTION,
                                                     "URL"        =>EMAIL_HOST,
                                                     "IMG"        =>EMAIL_HOST . "/Media/images/logoTag.png",
                                                     "FB_APP_ID"  =>"2222222222",
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


//  if (PHP_SAPI === "cli") { //if (!isset($_SERVER['HTTP_HOST'])) {
//  trigger_error(' Executing Tests , PHPUnit ');
//die('This script cannot be run from the CLI. Run it from a browser.');
//  }

unset( $_path );