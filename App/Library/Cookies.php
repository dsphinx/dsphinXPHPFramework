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

    class Cookies
    {
        const Session    = null;
        const OneDay     = 86400;
        const SevenDays  = 604800;
        const ThirtyDays = 2592000;
        const SixMonths  = 15811200;
        const OneYear    = 31536000;
        const Lifetime   = -1; // 2030-01-01 00:00:00

        /**
         * Returns true if there is a cookie with this name.
         *
         * @param string $name
         *
         * @return bool
         */
        static public function Exists($name)
        {
            return isset($_COOKIE[$name]);
        }

        /**
         * Returns true if there no cookie with this name or it's empty, or 0,
         * or a few other things. Check http://php.net/empty for a full list.
         *
         * @param string $name
         *
         * @return bool
         */
        static public function IsEmpty($name)
        {
            return empty($_COOKIE[$name]);
        }

        /**
         * Get the value of the given cookie. If the cookie does not exist the value
         * of $default will be returned.
         *
         * @param string $name
         * @param string $default
         *
         * @return mixed
         */
        static public function Get($name, $default = '')
        {
            return (isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default);
        }

        /**
         * Set a cookie. Silently does nothing if headers have already been sent.
         *
         * @param string $name
         * @param string $value
         * @param mixed  $expiry
         * @param string $path
         * @param string $domain
         *
         * @return bool
         */
        static public function Set($name, $value, $expiry = self::Session, $path = '/', $domain = FALSE)
        {
            $retval = FALSE;
            if (!headers_sent()) {
                if ($domain === FALSE)
                    $domain = $_SERVER['HTTP_HOST'];

                if ($expiry === -1)
                    $expiry = 1893456000; // Lifetime = 2030-01-01 00:00:00
                elseif (is_numeric($expiry))
                    $expiry += time(); else
                    $expiry = strtotime($expiry);

                $retval = @setcookie($name, $value, $expiry, $path, $domain);
                if ($retval)
                    $_COOKIE[$name] = $value;
            }

            return $retval;
        }

        /**
         * Delete a cookie.
         *
         * @param string $name
         * @param string $path
         * @param string $domain
         * @param bool   $remove_from_global Set to true to remove this cookie from this request.
         *
         * @return bool
         */
        static public function Delete($name, $path = '/', $domain = FALSE, $remove_from_global = FALSE)
        {
            $retval = FALSE;
            if (!headers_sent()) {
                if ($domain === FALSE)
                    $domain = $_SERVER['HTTP_HOST'];
                $retval = setcookie($name, '', time() - 3600, $path, $domain);

                if ($remove_from_global)
                    unset($_COOKIE[$name]);
            }

            return $retval;
        }
    }

