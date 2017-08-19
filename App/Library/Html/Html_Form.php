<?php
    /**
     * difF PHP Framework :: Html_Form.php
     *
     * @version: 0.5
     * @date   : 16/08/2012
     * @author Melisides Constantinos (dsphinx@gmail.com)
     * @require    : html.php
     * @Description:    html 5 features
     *                        HTML 5 session
     *
     *
     * Licensed under MIT licence:
     *   http://www.opensource.org/licenses/mit-license.php
     **/


    class Html_Form
    {

        static $_key_name = '_call_form';


        static function esc_url($url)
        {

            if ('' == $url) {
                return $url;
            }

            $url   = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
            $strip = array('%0d', '%0a', '%0D', '%0A');
            $url   = (string)$url;

            $count = 1;
            while ($count) {
                $url = str_replace($strip, '', $url, $count);
            }

            $url = str_replace(';//', '://', $url);
            $url = htmlentities($url);
            $url = str_replace('&amp;', '&#038;', $url);
            $url = str_replace("'", '&#039;', $url);

            if ($url[0] !== '/') {
                // We're only interested in relative links from $_SERVER['PHP_SELF']
                return '';
            } else {
                return $url;
            }
        }


        /**
         * @return string
         *
         *
         *  Generate md5 hash for protecting FORM POSTS
         *
         */
        static function _set_call($keytoset = NULL)
        {
            $keytoset            = isset($keytoset) ? $keytoset : self::$_key_name;
            //$_FORM_KEY           = substr(md5(__DIR__ . __METHOD__ . time()),0,24);
            $_FORM_KEY           = md5(__DIR__ . __METHOD__ . time());
            $_SESSION[$keytoset] = $_FORM_KEY;

            return '<input type="hidden" name="' . $keytoset . '" id="' . $keytoset . '" value="' . $_FORM_KEY . '">';
        }


        /**
         * @param      $inputkey
         * @param null $keytoset
         *
         *
         *
         * @return bool
         *
         *
         */
        static function _is_normal_call($inputkey, $keytoset = NULL)
        {
            $_return  = FALSE;
            $keytoset = isset($keytoset) ? $keytoset : self::$_key_name;
            if (isset($_SESSION[$keytoset]) && $inputkey === $_SESSION[$keytoset]) {
                $_return = TRUE;
            }

            return $_return;
        }


    }

