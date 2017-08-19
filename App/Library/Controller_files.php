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
class Controller_files extends Template
{

	// When is True, Controller doesnt load all files .js .cs .html
	static $stopLoadingRelativesFileFromDirectory=FALSE;

	/**
	 * Remove HTML tags, including invisible text such as style and
	 * script code, and embedded objects.  Add line breaks around
	 * block-level tags to prevent word joining after tag removal.
	 */
	static public function strip_html_tags($text)
	{
		$_HTML5_ALLOW_TAGS='<form><label><link><script><style><button><fieldset><legend><input><span><a><abbr><acronym><br/><address><article><aside><b><bdo><big><blockquote><br><caption><cite><code><col><colgroup><dd><del><details><dfn><div><dl><dt><em><figcaption><figure><font><li><h1><h2><h3><h4><h5><h6><hgroup><hr><i><img><ins><li><map><mark><menu><meter><ol><p><pre><q><rp><rt><ruby><s><samp><section><small><span><strong><style><sub><summary><sup><table><tbody><td><tfoot><th><thead><time><tr><tt><u><ul><var><wbr>';

		return strip_tags($text, $_HTML5_ALLOW_TAGS);
	}


	/**
	 * @param string $filename
	 * @param string $doc_type
	 * @param bool   $nohead
	 *
	 *
	 *   return plain text/ html file
	 *
	 * @return string
	 */
	static public function get_plain_file($filename="", $doc_type=".htm", $nohead=FALSE)
	{
		$contents='';
		if ( $urlh=@fopen($filename, 'rb') ) {
			$contents=stream_get_contents($urlh);
		}

		if ( stripos($filename, $doc_type) > 0 && $nohead ) { //  strip_tags Remove head body

			$contents=self::strip_html_tags($contents);

		} else
			$contents='<pre>' . $contents . '</pre>'; // "txt", "info"

		$_SESSION['PATHS']['FRAMEWORK']['CONTROLLER']=__METHOD__;

		return $contents;
	}


	/**
	 * @param string $key
	 * @param        $file
	 *
	 *
	 *   only members/ logded in can access   PATH MEMBERS
	 *
	 * @return bool
	 *
	 */
	static public function _Execute_Registered($key="", $file)
	{
		$ret=FALSE;
		if ( $key == "MEMBERS" ) if ( !isset( $_SESSION['Auth']['User_Session_Id'] ) ) //			if (!isset($_SESSION['HTML5']))
			$ret=TRUE;

		return $ret;
	}


	/**
	 * @param null $pageto
	 *
	 *  return PHP or HTML code, depending on what is existed
	 *
	 *
	 * @return bool|mixed|string
	 */
	static public function _Execute($pageto=NULL)
	{

		$html=NULL;
		$exts=array("php",
		            "html",
		            "htm"
		);
		// $exts= array( "php5", "php", "htm" );

		while ( list( $key, $value )=each($_SESSION['PATHS']['FILES']) ) {
			$possible=$_SESSION['PATHS']['__ROOT__'] . $value;

			if ( !is_dir($possible) ) continue;

			if ( self::_Execute_Registered($key, $possible) ) // Members area onto $_SESSION['User_Session_Id']
				continue;

			foreach ( $exts as $extension ) {
				$fileto="$possible$pageto.$extension";

				if ( file_exists($fileto) ) {
					if ( $extension == "php" ) {
						$html=self::_Execute_php_noMVC($fileto); //  echo , printf, output ///
						if ( !$html ) //  function name or  class
							$html=self::_Execute__php($fileto);
					} else {
						$html=Semantic::section(self::_Execute_html($fileto, $extension));
					}
				}
			}
			if ( is_dir($possible . $pageto) && !$html ) { // plugins folder name
				$fileto="$possible$pageto/$pageto.php"; // same name folder and php file
				if ( file_exists($fileto) ) {
					$html=self::_Execute_php_noMVC($fileto);
					if ( !$html ) //  function name or  class
						$html=self::_Execute__php($fileto);
				}
			}
		}

		$_SESSION['PATHS']['FRAMEWORK']['CONTROLLER']=__METHOD__;

		return $html;
	}

	static public function _Execute_html($filename)
	{
		return self::get_plain_file($filename, "html", TRUE);
	}

	/**
	 * @param $phpfile
	 *
	 *  return PHP code from simple PHP file or Class objects or functions
	 *
	 *  examples on \TESTING_ONLY directory
	 *
	 * @return mixed|string
	 */
	static public function _Execute__php($phpfile)
	{

		$ret="";
		require_once( $phpfile );

		$evalcode=basename($phpfile, ".php");
		if ( class_exists($evalcode) ) {
			$foo=new $evalcode;
			$ret=call_user_func_array(array($foo,
			                                "$evalcode"
			), array(''));
		}

		if ( function_exists($evalcode) ) $ret=call_user_func_array($evalcode, array(''));

		$_SESSION['PATHS']['FRAMEWORK']['CONTROLLER']=__METHOD__;

		//		echo " Loading evalution code from php file - dangerous ".__FILE__. __LINE__. __FUNCTION__;
		return $ret;
	}


	/**
	 * @param $_html
	 *
	 *   Return in Array all [
	 *
	 * @blocks] from .html file in order to replace with vars
	 *                       [@code]
	 * @return mixed array
	 */

	static public function _Get_design_block($_html)
	{
		preg_match_all('/(?!\b)(\[@\w+\b\])/', $_html, $matches);

		return ( $matches[0] );
	}


	/**
	 * @param $filename
	 *
	 *  files requires $filename.php
	 *                   $filename.html
	 *
	 *  PHP file is executing normaly , and passed @variables to .html file
	 *
	 * eg:
	 * php :     $FORM_KEY  = ' test';
	 * html:        [@FORM_KEY]
	 *
	 *
	 *  return $filename.PHP Output file into our template , without using this MVC
	 *         also return $filename.html files with block replacement
	 *
	 *                                    .php.html                  .php
	 *                                      .html
	 *                  *                [@code]                     $code
	 *
	 * @return bool|string
	 */
	static public function _Execute_php_noMVC($filename)
	{
		$_html_file=NULL;

		$_SESSION['PATHS']['FRAMEWORK']['CONTROLLER']=__METHOD__;
		if ( is_file($filename) ) {

			ob_start();
			include $filename; // PHP file

			/**
			 *   Load  filename.php.html file           filename.php
			 *            filename.html
			 *                [@code]                     $code
			 *
			 */
			//$filename .= ".html";
			$filename=str_replace(".php", ".html", $filename);


			if ( !self::$stopLoadingRelativesFileFromDirectory && is_file($filename) ) {
				$_html_file=Controller::get_plain_file($filename, 'html', TRUE);
				if ( $_html_file ) {
					$blocks=self::_Get_design_block($_html_file);

					foreach ( $blocks as $var ) {
						$var_from_php=preg_replace(array('/\@/',
						                                 '/\[/',
						                                 '/\]/'
						), '', $var);
						if ( isset( $var_from_php ) ) {
							$_html_file=str_replace($var, $$var_from_php, $_html_file);
						}
					}
				}
				Echoc::output($_html_file);
				Controller_files::_loadAllFile_fromDirectory(dirname($filename), $filename);
			}

			return ob_get_clean();
		}

		return FALSE;
	}


	/**
	 * @param $directory
	 *
	 *
	 * Load all *.css , *.js , *.html in given directory
	 *
	 *
	 */
	static public function _loadAllFile_fromDirectory($directory, $mainPHP='')
	{

		$_tmp          =explode($_SESSION['PATHS']['__ROOT__'], $directory);
		$_relative_path=$_tmp[1] . DIRECTORY_SEPARATOR;
		$mainPHP       =basename(str_replace('php', 'html', $mainPHP));


		$directory_is=new DirectoryIterator($directory);

		foreach ( $directory_is as $fileinfo ) {
			if ( $fileinfo->isFile() ) {

				$extension=strtolower($fileinfo->getExtension());
				$fname    =$fileinfo->getFilename();

				$iscss =stripos($extension, "css");
				$isjsp =stripos($extension, "js");
				$ishtml=stripos($extension, "html");

				//Controller::debugInfo(" $_relative_path .$fname");

				if ( $iscss !== FALSE ) {
					Echoc::output('<link href="' . $_relative_path . $fname . '" rel="stylesheet">');
				}

				if ( $isjsp !== FALSE ) {
					Echoc::output('<script src="' . $_relative_path . $fname . '" async> </script>');
				}

				if ( $ishtml !== FALSE ) {

					if ( $mainPHP != $fname ) {
						$html=self::_Execute_html($_relative_path . $fname);
						$_tmp=Template_files::View($html);
						//     echo '<pre>'.$_tmp.'</pre>';
						Echoc::output($html);
					}

				}

			}
		}


	}

	/**
	 *
	 *  Show PHP Code , highlight color
	 *                  via built in highlight_file
	 *                  css class showPHPCode
	 *
	 * @param $file
	 */
	static public function  showPHPCode($file, $print=TRUE)
	{
		require_once 'Unix/Unix.php';

		if ( is_file($file) ) {
			//Strip code and first span
			$code=substr(highlight_file($file, TRUE), 36, -15);
			$html     ='';
			$bytes    =filesize($file);
			$bytes    =Unixfile::show_human($bytes);
		} else {
			$code=highlight_string($file, TRUE);
			$bytes = strlen($file);
			$file ="Source Code";
		}

		//Split lines
		$lines=explode('<br />', $code);
		//Count
		$lineCount=count($lines);
		//Calc pad length
		$padLength=strlen($lineCount);


		//Re-Print the code and span again
		$html.="<div class='showPHPCode'> <span class=\"showPHPCodeLines\"> File</span> $file <br/>
 										  <span class=\"showPHPCodeLines\"> Lines</span> $lineCount <span class=\"showPHPCodeLines\"> Size </span> $bytes
		</div>
		<div class='showPHPCodeListing'> <code style='background-color: white;'><span style=\"color: #000000\">";

		//Loop lines
		foreach ( $lines as $i=>$line ) {
			//Create line number
			$lineNumber=str_pad($i + 1, $padLength, '0', STR_PAD_LEFT);
			//Print line
			$html.=sprintf('<br /><span class="noselect showPHPCodeLines" >%s</span><span class="noselect"> </span>%s', $lineNumber, $line); // │
		}

		//Close span
		$html.="</span></code></div>";
		if ( $print ) {
			echo $html;
		}

		return $html;
	}


	/**
	 * @return array
	 *
	 *    get all files that is loaded from interpreter
	 */
	static public function getAllFileIncluded()
	{
		return array_merge(get_included_files());
	}

}