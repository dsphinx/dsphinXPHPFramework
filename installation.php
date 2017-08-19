<?php
/**
 *  Copyright (c) 2015, dsphinx
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
 *  Filename: installation.php
 *  Created : 24/8/15 6:11 PM
 */


DEFINE ( "__AJAX", TRUE );  //   Do not use Database Sessions, yet


require_once( 'App/initialize.php' );


AppCookieStrategy::$_preferences['templateTheme']='installation';


$section=Input::In_GET_Url('section');
$html   ='';

Controller::run();



/**
 *
 *
 */

switch ( $section ) {
	case 'installation_config':
		$f='installation/configuration.php';
		Controller::showPHPCode($f);
		$html = " You must replace this file to App/config/ ";
		break;
	case 'installation_dependecies';
		echo " SESSION are ";
		Echoc::object($_SESSION);
		break;

	case 'installation_database':

		/**
		 *
		 *   Install
		 */

		if (!MyDB::isConnectionOK()) {

			echo Html::_error_light("MYSQL Error "," Cant connect to mysql ");

		} else {

			require_once 'App/Library/Mysql/MysqlBackup.php';

			MysqlBackup::exportCompressFile("/tmp/aa.bz2"); // take backup

		//	if (MysqlBackup::restoreFile( __DIR__."/installation/installation.sql") != 0) {
		//		echo Html::_error_light("MYSQL Error "," failed to export file ");
	//		}

		}



		$html = " Mysql support 5 >";
		Controller::showPHPCode('installation/installation.sql');
	default :
		break;
}

//Echoc::object(Controller::$_page);

if (!Controller::$_page['run']) {
	Controller::runHtmlCode($html,' Framework Installation');
}


