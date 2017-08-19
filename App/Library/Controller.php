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
class Controller extends Controller_files
{

	const   URL_GET_PARAM = "page"; // $_GET param to load pages
	const   DEFAULT_PAGE = "main"; // $_GET param to load pages

	static $_page_template;
	static $_page_languageID;

	/**
	 * @var array
	 *  page run    ?page=xxx&run=xxxx&section=--
	 *
	 */
	static $_page = array('run' => "", 'cmd' => "", 'section' => "", "article" => "");

	static $_page_css = array( //  Our Base Css files
		'normalize.css', 'bootstrap.min.css', //  'bootstrap-theme.min.css',
		'layout.css', 'menus.css'// Fonts
	);

	/*
	 *   Google fonts  --- load wev fonts ---
	 *      .css file
	 */
	static $_page_fonts = array(//   'http://fonts.googleapis.com/css?family=Roboto+Condensed'
	);

	static $_page_javascript = array( // Base Javascript
		'jquery.min.js', // 'jquery-ui.min.js',
		'bootstrap.min.js', //  'jquery.chosen.min.js',
		// 'jquery.lazyload.min.js',
	//	'jquery.smartmenus.min.js',
		'main.js',

	);


	/**
	 *
	 *  Get input params from URL
	 *  main
	 *              ?page=<page_to_load>
	 *           or ?<page_to_load>
	 */
	static public function init()
	{
		self::$_page['run'] = Input::In_GET_Url(self::URL_GET_PARAM);

		if ( !self::$_page['run'] ) {
			$query = explode('&', htmlspecialchars($_SERVER['QUERY_STRING']));
			self::$_page['run'] = !empty( $query[0] ) ? $query[0] : self::DEFAULT_PAGE;
		}

		self::$_page['article'] = Input::In_GET_Url('article');
		self::$_page['cmd'] = Input::In_GET_Url('cmd');
		self::$_page['section'] = Input::In_GET_Url('section');
		self::$_page_template = new Template();

		if ( $_SESSION['PATHS']['LAST_URL'] != "lang" ) {
			$_SESSION['PATHS']['LAST_URL'] = self::$_page['run'];
		}


	}

	/**
	 *     no HTML5 support
	 *
	 */
	public static function run_on_old_browser()
	{
		$tmp = new Browser();
		$_return = NULL;

		if ( !$tmp->HTML5() ) {

			$_return .= '    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                                    <!--[if lt IE 9]>
                                    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                                    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
                                    <![endif]--> ';

			$_return = self::javascript('no_html5/modernizr.full.js');
			$_return = self::javascript('no_html5/ie_PIE.js');
			$_return .= self::header_on_noHTML5();
			echo self::_error_light("incompatible Web Browser", AppMessages::Show("nohtml5browser", FALSE));
			$_return .= '<!-- ' . $tmp->getBrowser() . ' ver ' . $tmp->getVersion() . ' ' . $tmp->getPlatform() . $tmp->getUserAgent() . ' -->';

		}


		return $_return;
	}


	/**
	 * @return mixed
	 *
	 *   return Main Page Code , include Template
	 *
	 */

	static public function create_page()
	{

		$_show_file = self::loadPageFromDB(self::$_page['run']);

		if ( !$_show_file ) {
			$_show_file = self::_Execute(self::$_page['run']);

			if ( self::$_page['run'] && !$_show_file  && self::$_page['run']!="this"  ) {
				$_show_file = self::_error("Page not found", AppMessages::Show("PageNotfound", FALSE));
			}
		}

		self::$_page_template->_autorun_template();
		self::$_page_template->set('page', $_show_file);
		self::$_page_template->set('copyright', COPYRIGHT);


		return self::$_page_template->output();
	}


	/**
	 * @return int
	 *
	 *    return current Language ID from session
	 *
	 */
	static public function getLanguageID()
	{

		$db = MyDB::db();

		$db->where("name", $_SESSION['LANGUAGE']);
		$sqlCmd = 'SELECT id FROM Languages  ';
		$tmp = $db->query($sqlCmd);
		self::$_page_languageID = $tmp[0]['id'] ? $tmp[0]['id'] : 1;

		return self::$_page_languageID;
	}


	/**
	 * @return mixed
	 *
	 *    return Page from store DB value
	 *
	 *
	 */
	static public function loadPageFromDB($pageNameDB)
	{
		$ret = NULL;
		$db = MyDB::db();

		/**
		 * TODO:
		 *
		 *    mysql DBPages
		 *    LAnguages
		 */

		return;

		$db->where("name", $pageNameDB)->where("trash", 0);
		$sqlCmd = 'SELECT id FROM DBPages   ';
		$tmp = $db->query($sqlCmd);

		if ( $tmp ) {

			$sqlCmd = "SELECT Contents_id FROM Contents_has_Labels WHERE Labels_id=" . $tmp[0]['id'];
			$row_articles = $db->query($sqlCmd);

			if ( $row_articles ) {

				$sqlCmd = "SELECT title,  content
                                    FROM Contents_lang, Contents
                                    WHERE Contents_lang.Contents_id=Contents.id AND Contents_id=" . $row_articles[0]['Contents_id'] . " AND
                                    Contents_lang.Languages_id=" . self::$_page_languageID . "  AND del=0  ";

				if ( !$row = $db->query($sqlCmd) ) {
					/*
					 *  fallback to english site
					 */
					$sqlCmd = "SELECT title,  content
                                    FROM Contents_lang, Contents
                                    WHERE Contents_lang.Contents_id=Contents.id AND Contents_id=" . $row_articles[0]['Contents_id'] . " AND
                                    Contents_lang.Languages_id=1  AND del=0  ";
					$row = $db->query($sqlCmd);
				}

				$ret = $row[0]['content'];
			}

		}


		return $ret;
	}


	/**
	 *
	 *  Main Action
	 *
	 */
	static public function run()
	{

		self::init();

		/*
		 *  Make permanent static link on Mysql
		 */
		MyDB::init_db();

		$nss = new Html5();

		$_html = $nss->Header();

		$_html .= self::run_on_old_browser();

		foreach ( self::$_page_javascript as $file ) $_html .= Html::javascript($file);

		foreach ( self::$_page_css as $file ) $_html .= Html::css($file);

		self::getLanguageID();

		if ( isset( self::$_page_fonts[0] ) ) {
			$_html .= Html::webFonts(self::$_page_fonts[0]);
		}

		$_html .= Html::body();

		Echoc::output($_html);

		$_html = self::create_page();

		if ( RUNNING_MODE != "sandbox" ) {
			$_html .= Html::javascript("google_analytics.js", "async");
		}
		$_html .= self::runDestruct();
		$_html .= $nss->Footer();

		/*
		 *  Cookies Post Load Functions
		 */
		require_once( 'App/Initialization/postInitialization.php' ); //  Cookies

		/*
		 *   HTML output END
		 */
		Echoc::output($_html);
		/*
		 *   status all included
		 * 	// Echoc::object(self::getAllFileIncluded()  );
		 */
	}


	/**
	 * @param $html
	 *
	 *   display Html code  on main div element
	 */
	static public function runHtmlCode($html, $title='') {

		Echoc::output("<script> contentShowHtml('$html','$title'); </script>");
	}


	static public function runDestruct()
	{
		$isIT = isset( $_SESSION ['SOCIAL_HEADER']['TITLE'] ) ? TRUE : FALSE;

		$ret = $isIT ? "<script> social('" . htmlspecialchars($_SESSION['SOCIAL_HEADER']['TITLE']) . "','" . $_SESSION['SOCIAL_HEADER']['DESCRIPTION'] . "','" . $_SESSION['PATHS']['SOCIAL_HEADER']['URL'] . "','" . $_SESSION['PATHS']['SOCIAL_HEADER']['IMG'] . "'); </script>" : NULL;

		return $ret;
	}

	/**
	 * @param null $url_paramert
	 *
	 *  Get URL safe param   &cmd= &section
	 *
	 * @return null
	 */

	static public function param($url_paramert = NULL)
	{
		$_ret = NULL;

		if ( array_key_exists($url_paramert, self::$_page) ) {
			$_ret = self::$_page[$url_paramert];
		} else {
			$_ret = Input::In_GET_Url($url_paramert);
		}

		return $_ret;
	}


	/**
	 * @param string $url_paramert
	 *
	 *   Get URL safe parameter INTEGER
	 *
	 */
	static public function param_integer($url_paramert = "id")
	{

		$num = Input::In_GET_Url($url_paramert);
		if ( !ctype_digit($num) ) {
			$num = FALSE;
		}

		return $num;
	}


	/**
	 * @param        $level_required
	 * @param string $title
	 * @param string $message
	 *
	 *
	 *   Return Role Based Auth policy
	 *
	 *
	 *  Examples:
	 *
	 *
	 * integer:
	 * if (Controller::Policy(0, " Admin Pages")) {  return;  }
	 *
	 *
	 * string:
	 * if (Controller::Policy("sendmail", " Admin Pages")) {  return;  }
	 *
	 *
	 *
	 */
	static public function Policy($level_required, $page = "", $title = "Security", $message = ' Not enough Privileges, to access !')
	{

		$_ret = TRUE;
		if ( !is_null($_SESSION['Auth']['Level']) && isset( $_SESSION['Auth']['UserName'] ) ) {
			require_once( 'App/Library/Auth/Auth.php' );
			require_once( 'App/Library/Auth/Auth_Policy.php' );
			$_ret = Policy::Access($level_required, $title, "$message  $page");
		}

		return $_ret;
	}


	/**
	 * @param        $action
	 * @param string $page
	 * @param string $title
	 * @param string $message
	 *
	 * @return bool
	 */
	static public function PolicyNOTAllowedActionExcept($action, $page = "Actions ", $title = "Permissions Rules ", $message = ' Not enough Permissions, to access !')
	{
		require_once( 'App/Library/Auth/Auth.php' );
		require_once( 'App/Library/Auth/Auth_Policy.php' );
		return Policy::AccessAction($action, $page, $title, $message);
	}


	/**
	 *   Load Modules
	 *
	 *   Modulename must have inial smae name on file
	 *
	 * @param null $modulename
	 */
	static public function addModule($modulename = NULL)
	{
		if ( $modulename ) {
			$path = $_SESSION['PATHS']['MODULES']['SET'] . $modulename . DIRECTORY_SEPARATOR . $modulename . ".php";
			if ( file_exists($path) ) {
				include_once( $path );
			}
			// echo $path;
		}
	}


	/**
	 *   Return Url without params &
	 *
	 * @return mixed
	 */
	static public function getUrl()
	{
		$_url = $_SERVER["REQUEST_URI"];
		$_tmp = explode("&", $_url);
		$_url = $_tmp[0];

		return $_url;
	}

	/**
	 *
	 *   Refresh url
	 *
	 * @param $urlParam .$url
	 */
	static public function refreshPage($urlParam = NULL, $url = NULL)
	{
		$url = isset( $url ) ? $url : $_url = $_SERVER["REQUEST_URI"];
		$urlParam = isset( $urlParam ) ? $urlParam : NULL;

		if ( $urlParam ) {
			$urlTmp = explode("&", $url);
			$url = NULL;
			foreach ( $urlTmp as $r ) {
				if ( !stristr($r, $urlParam) ) {
					$url .= $r . "&";
				}
			}
			// $url=substr_replace($url, NULL, -1);
		}

		echo '<meta HTTP-EQUIV="REFRESH" content="0; url=' . $url . '">';
	}

	/**
	 * @return bool
	 *
	 *   Return if is Running on sandbox to Development issues
	 */
	static public function isRunningSandbox()
	{
		// return FALSE;
		return ( RUNNING_MODE == "sandbox" ? TRUE : FALSE );
	}

	/**
	 * @param string $file
	 * @param int    $line
	 * @param        $message
	 *
	 *   display debug message
	 */
	static public function debugInfo($file = __FILE__, $line = __LINE__, $message)
	{

		if ( self::isRunningSandbox() ) {
			$file = basename($file);
			echo "<small style=\"color: darkblue;\">$file</small><b style=\"color: red;\"> [ </b><mark>$line</mark><b style=\"color: red;\"> ]</b> $message <br/>";
			trigger_error("$file:$line -->  $message");
		}
	}


}