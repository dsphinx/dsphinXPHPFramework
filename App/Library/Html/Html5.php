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


    class Html5 extends Html
    {
        private $_HTML5String = NULL;
        private $_EOL;

        public function e($txt)
        {
            $this->_HTML5String .= $txt . $this->_EOL;
        }

        /**
         * @param null $title
         * @param null $myEOL
         *
         *  Return HTML5 valid header tag
         *
         * HTML 5 ONLY ***
         *
         *      http://validator.w3.org/
         *
         * @return null
         */
        public function Header($title = NULL, $myEOL = NULL)
        {

            $this->_EOL = isset($myEOL) ? $myEOL : self::HTML_EOL;

            $this->e('<!DOCTYPE html>');


            if (SEMANTIC_WEB) {

                $this->e('<html lang="en" prefix="dc: '. Semantic::getURI('content').'"> ');
                $this->e('<head profile="'.Semantic::getURI('content').'">');

                $this->e('<link rel="dc:subject" href="http://dbpedia.org/resource/Semantic_Web" /> ');
                $this->e('<link rel="dc:subject" href="http://dbpedia.org/resource/RDFa" /> ');
                $this->e('<link rel="dc:subject" href="http://dbpedia.org/resource/HTML5" /> ');

            } else {

                $this->e('<html>');
                $this->e('<head>');

            }

            // $this->e('<html manifest=”App/manifest.php”>');      // Cache ?
            $this->e('<meta charset="utf-8" />');
            $this->e('<meta name="viewport" content="width=device-width, initial-scale=1.0" />');

            self::Header_on_Mobile();


            //	$this->e('<link rel="license" href="http://www.opensource.org/licenses/mit-license.php" />');
            $this->e('<meta  name="author" content="' . AUTHOR . '" />');
           //  $this->e('<meta rel="dc:creator" href="' . AUTHOR . '" />');

            // $this->e('<base href="'.Controller::getUrl().'"   />'); // wtih AJAX is problematic, refreshing

            $this->e('<meta rel="dc:subject" name="description" content="' . DESCRIPTION . '" />');
            $this->e('<meta rel="dc:subject" name="keywords" content="' . KEYWORDS . '" />');
            $this->e('<meta name="generator" content="diff Framework  : ' . AUTHOR . ' " />');
            $this->e('<meta name="copyright" content=" ' . COPYRIGHT . '  " />');
            $this->e('<meta name="robots" content="index,follow" />');

            $this->e('<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />');
            $this->e('<title  property="dc:title">' . self::get_title($title) . '</title>');
            $this->e('<link rel="author"  href="' . AUTHOR . '"  />');
            $this->e('<link rel="meta" type="application/rdf+xml" title="FOAF" href="' . AUTHOR_FOAF . '" />');

            $this->e('<link rel="shortcut icon" type="image/x-icon" href="Media/images/favicon.ico"  />');
            $this->e('<link rel="icon" type="image/x-icon" href="Media/images/favicon.ico"  />');

            if (self::is_social_media_page()) {
                $this->e(self::facebook_header());
                $this->e(self::twitter_header());
            }

            return $this->_HTML5String;
        }


        public function Footer($last_html_code = NULL)
        {
            $this->_HTML5String = $this->_EOL;
            $this->_HTML5String .= isset($last_html_code) ? $last_html_code . $this->_EOL : NULL;


            $this->e('</body>');
            $this->e('</html>');

            return $this->_HTML5String;
        }




        public function Header_on_Mobile()
        {

            if (!self::isMobile() && !self::isTablet()) {
                return;
            }

            $this->e(HTML5_Mobile::Header());


            // ￼<meta name="apple-mobile-web-application- status-bar-style" content="black">

        }

    }