<?php
/**
 *  Copyright (c) 29/12/14 , dsphinx@plug.gr
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
 *  DISCLAIMED. IN NO EVENT SHALL dsphinx BE LIABLE FOR ANY
 *  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 *  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 *  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 *  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *  Created : 9:24 AM - 29/12/14
 *
 */


/*
 *    Ajax Calls , and controller,Html mini support
 *
 *    minified versions , no sessions
 */

DEFINE ( "__AJAX", TRUE );
require_once( __DIR__ . '/Ajax.php' );
require_once( __DIR__ . '/../Init.php' );

Init::_add_include_path($_SESSION['PATHS']['__ROOT__']);
Init::_add_include_path($_SESSION['PATHS']['LIBRARIES']);

Init::_librariesAjax();

MyDB::init_db();

if ( !class_exists("Controller") ) {

	/**
	 * Class Controller   mini
	 *
	 */
	class Controller extends Ajax_Call
	{
		const   URL_GET_PARAM="page";
		const   DEFAULT_PAGE="main";

		static $_page=array('run'    =>"",
		                    'cmd'    =>"",
		                    'section'=>"",
		                    'article'=>""
		);


		static public function init()
		{
			self::$_page['run']=self::IN(self::URL_GET_PARAM);

			if ( !self::$_page['run'] ) {
				$query             =explode('&', htmlspecialchars($_SERVER['QUERY_STRING']));
				self::$_page['run']=!empty( $query[0] ) ? $query[0] : self::DEFAULT_PAGE;
			}

			self::$_page['article']=self::IN('article');
			self::$_page['cmd']    =self::IN('cmd');
			self::$_page['section']=isset( $_GET['section'] ) ? $_GET['section'] : NULL;

		}

		static public function param($url_paramert=NULL)
		{
			$_ret=NULL;

			if ( array_key_exists($url_paramert, self::$_page) ) {
				$_ret=self::$_page[$url_paramert];
			} else {
				$_ret=isset( $_GET[$url_paramert] ) ? $_GET[$url_paramert] : NULL;
			}

			return $_ret;
		}

		static public function param_integer($url_paramert="id")
		{
			$num=isset( $_GET[$url_paramert] ) ? $_GET[$url_paramert] : NULL;

			if ( !ctype_digit($num) ) {
				$num=FALSE;
			}

			return $num;
		}

	}


	Controller::init();


}

if ( !class_exists("Html") ) {

	class Html
	{
		public static function _info($title, $mes)
		{
			return '<div class="alert alert-info" > <strong> ' . $title . ' </strong>            ' . $mes . '
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                   </div> ';
		}

		public static function _error($title, $mes)
		{
			return '<div class="alert alert-danger"  style="margin: auto; width: 90%; ">  ' . $title . '  </div>
                    <div class="well well-large"  style="margin: auto; width: 90%; ">
                         <p class="text-left">  ' . $mes . ' </p>
                    </div> ';
		}

		public static function _error_light($title, $mes)
		{
			return '<div class="alert " > <strong> ' . $title . ' </strong>            ' . $mes . '
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                   </div> ';
		}

	}

}

if ( !class_exists("Browser") ) {

	class Browser
	{
		public static function _info($title, $mes)
		{
			return '<div class="alert alert-info" > <strong> ' . $title . ' </strong>            ' . $mes . '
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                   </div> ';
		}

		public static function _error($title, $mes)
		{
			return '<div class="alert alert-danger"  style="margin: auto; width: 90%; ">  ' . $title . '  </div>
                    <div class="well well-large"  style="margin: auto; width: 90%; ">
                         <p class="text-left">  ' . $mes . ' </p>
                    </div> ';
		}

		public static function _error_light($title, $mes)
		{
			return '<div class="alert " > <strong> ' . $title . ' </strong>            ' . $mes . '
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                   </div> ';
		}

	}

}

require_once( __DIR__ . '/../Html/Html_Social.php' );
