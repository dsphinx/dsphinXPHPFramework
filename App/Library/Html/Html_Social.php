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

    class Html_Social_Media extends Browser
    {

        static public function setInfo($title = NULL, $desc = NULL, $url = NULL, $img = NULL)
        {
            $_SESSION['SOCIAL_HEADER']['TITLE']       = $title;
            $_SESSION['SOCIAL_HEADER']['DESCRIPTION'] = $desc;
            $_SESSION['SOCIAL_HEADER']['URL']         = $url;
            $_SESSION['SOCIAL_HEADER']['IMG']         = $img;
        }

        static public function is_social_media_page()
        {
            if (isset($_GET['title'])) {
                $_SESSION['PATHS']['SOCIAL_HEADER']['TITLE']       = $_GET['title'];
                $_SESSION['PATHS']['SOCIAL_HEADER']['URL']         = $_SERVER["REQUEST_URI"];
                $_SESSION['PATHS']['SOCIAL_HEADER']['DESCRIPTION'] = $_GET['title'];

            }

            return (isset($_SESSION['PATHS']['SOCIAL_HEADER']['TITLE']) ? TRUE : FALSE);
        }

        static public function facebook_header()
        {

            // el_GR

            return '<meta property="og:title" content="' . $_SESSION['PATHS']['SOCIAL_HEADER']['TITLE'] . '" />' . Echoc::$_EOL .
                '<meta property="og:description" content="' . $_SESSION['PATHS']['SOCIAL_HEADER']['DESCRIPTION'] . '" />' . Echoc::$_EOL .
                '<meta property="og:site_name" content="' . $_SESSION['PATHS']['SOCIAL_HEADER']['DESCRIPTION'] . '" />' . Echoc::$_EOL .
                '<meta property="og:type" content="article" />' . Echoc::$_EOL .
                '<meta property="og:locale" content="en_US" />' . Echoc::$_EOL .
                '<meta property="article:tag" content="' . $_SESSION['PATHS']['SOCIAL_HEADER']['TITLE'] . '" />' . Echoc::$_EOL .
                '<meta property="article:section" content="' . $_SESSION['PATHS']['SOCIAL_HEADER']['TITLE'] . '" />' . Echoc::$_EOL .
                '<meta property="article:published_time" content="' . date('Y-M-d') . '" />' . Echoc::$_EOL .
                '<meta property="og:url" content="' . $_SESSION['PATHS']['SOCIAL_HEADER']['URL'] . '" />' . Echoc::$_EOL .
                '<meta property="og:image" content="' . $_SESSION['PATHS']['SOCIAL_HEADER']['IMG'] . '" />' . Echoc::$_EOL .
                '<meta property="og:app_id" content="' . $_SESSION['PATHS']['SOCIAL_HEADER']['FB_APP_ID'] . '" />';

        }

        static public function  facebook_share($url = NULL)
        {
            $url = is_null($url) ? $_SESSION['PATHS']['SOCIAL_HEADER']['URL'] : $url;

            return '<a href="' . 'http://www.facebook.com/sharer.php?u=' . urlencode($url) . '" target="blank"><img src="Media/images/social/facebook_share.png"></a>';

        }

        static public function twitter_header()
        {

            return '<meta name="twitter:card" content="summary"/>' . Echoc::$_EOL .
                '<meta name="twitter:site" content="' . EMAIL_HOST . '"/>' . Echoc::$_EOL .
                '<meta name="twitter:domain" content="' . EMAIL_HOST . '"/>' . Echoc::$_EOL .
                '<meta name="twitter:creator" content="' . AUTHOR . '"/>' . Echoc::$_EOL;
        }

        static public function facebook_header_body($facebok_app_id = "")
        {

            return '<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=' . $facebok_app_id . '";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, "script", "facebook-jssdk"));</script>';
        }

        static public function like($url = "", $style = "")
        {

            return '<div class="fb-like" ' . $style . ' data-href="' . $url . '" data-send="false"
		data-layout="button_count" data-width="450" data-show-faces="false" data-action="like"></div>';
        }

        static public function getTweetUrl($url, $title)
        {
            $maxTitleLength = 140 - (strlen($url) + 1);
            if (strlen($title) > $maxTitleLength) {
                $title = substr($title, 0, ($maxTitleLength - 3)) . '...';
            }

            return "$title " . urlencode($url);
        }

        static public function tweet_this($url = NULL, $title = NULL)
        {
            $url   = is_null($url) ? $_SESSION['PATHS']['SOCIAL_HEADER']['URL'] : $url;
            $title = is_null($title) ? $_SESSION['PATHS']['SOCIAL_HEADER']['TITLE'] : $title;

            return '<a href="https://twitter.com/share" class="twitter-share-button" data-url="' . $url . '" data-text="' .
                $title . '">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';

        }


    }