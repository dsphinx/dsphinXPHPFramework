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

    require_once ('Html_Browser.php'); //  Browser Specific
    require_once ('Html_Social.php'); //  Browser Specific
    require_once ('Html_Form.php');
    require_once ('HtmlLink.php');


    class Html extends Html_Social_Media
    {

        const  HTML_EOL = "\n";

        /**
         * @param $cssfile
         *
         *  return HTML tag for CSS
         *   depends on $_SESSION[ 'PATHS' ][ 'TEMPLATE_DIR' ] variable
         *  file css must exist in
         *                           $_SESSION[ 'PATHS' ][ 'TEMPLATE_DIR' ] / template
         *
         * Problems ?
         *   DIRECTORY_SEPARATOR , on WAMP problems ? 50%-50% \ and /
         *
         * @return null|string
         */
        public static function css($cssfile, $template_theme_load = NULL)
        {

            $_ret     = NULL;
            $template = isset($template_theme_load) ? $template_theme_load : AppCookieStrategy::$_preferences['templateTheme'];

            $fullpath = $_SESSION['PATHS']['__ROOT__'] . '/' . $_SESSION['PATHS']['TEMPLATE_DIR'] . $template . '/' . 'css' . '/' . $cssfile;
            // $extra_old_nohtml5 = ' type="text/css"';

            if (@file_exists($fullpath)) {
                $_ret = '<link href="' . $_SESSION['PATHS']['TEMPLATE_DIR'] . $template . '/' . 'css' . '/' . $cssfile . '" rel="stylesheet">';

            }

            return ($_ret . self::HTML_EOL);
        }


        public static function webFonts($cssfile)
        {
            $_ret = '<link href="' . $cssfile . '" rel="stylesheet" type="text/css" >';

            return ($cssfile ? $_ret . self::HTML_EOL : NULL);
        }


        public static function body($comments = NULL)
        {
            $_ret = '</head>' . self::HTML_EOL . '<body vocab="'. Semantic::getURI('dc') .'">';
            $_ret .= ($comments) ? $comments . self::HTML_EOL : NULL;

            return $_ret;
        }


        public static function header_on_noHTML5()
        {

            return '<meta http-equiv="X-UA-Compatible" content="IE=9">' . self::javascript("modernizr.js") .
                '<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>' .
                '<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>';
        }

        /**
         * @param $jsp_file
         *
         * @return string
         *
         *    method async
         *          defer
         */
        public static function javascript($jsp_file, $method = NULL)
        {
            $_ret     = NULL;
            $fullpath = $_SESSION['PATHS']['__ROOT__'] . $_SESSION['PATHS']['JAVASCRIPT'] . $jsp_file;


            if (@file_exists($fullpath)) {
                $_ret = '<script src="' . $_SESSION['PATHS']['JAVASCRIPT'] . $jsp_file . '" ' . $method . '></script>';
            }

            return ($_ret . self::HTML_EOL);
        }


        public static function get_title($title = NULL)
        {
            return (self::is_social_media_page() ? $_SESSION['PATHS']['SOCIAL_HEADER']['TITLE'] :
                (isset($title) ? $title : TITLE));
        }


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


		public static function setWindowTitle($title)
			{

				if ( $title ) {
					echo '<script>  document.title = "' . $title . '"; </script>';
				}


			}


    }

    require_once ('Html5.php'); //  HTML5
    require_once ('Html5_Mobile.php'); //  Mobile

    require_once ('Html_Table.php');













