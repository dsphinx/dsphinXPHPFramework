<?php
    /**
     *  Copyright (c) 2014, dsphinx@plug.gr
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
     // Signin.js
     *
    //  me AES Encrytpe βγαζει salted password λαθος ... καίτ δεν κάνει καλά μα΄΄ον το  \0 null end of string κα΄ι κάνει
    // με σψρις AES είναι οπως παλία οκ ...
    // Αρχει  Auth/auth.php Auth/crypt.php  Api/Auth
    //  λινε sigin.js 65

    // Plain Text ok
       $.postJSON('App/Api/Auth.php', { user: name, pass: name2, call_service: 1 }, function (data, status, xhr) {
    AES Encrypted
        $.postJSON('App/Api/Auth.php', { user: name, pass: data_base64, call_service: 1, iv: iv_base64, key: key_base64 }, function (data, status, xhr) {

     *
     */

    class Crypt
    {

        static public function AES_Decrypt($pass, $pass_aes_iv, $pass_aes_key)
        {
            $_ret = $pass;

            if ($pass_aes_iv && $pass_aes_key) {

                $pass         = base64_decode($pass);
                $pass_aes_iv  = base64_decode($pass_aes_iv);
                $pass_aes_key = base64_decode($pass_aes_key);
                $_tmp         = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $pass_aes_key, $pass, MCRYPT_MODE_CBC, $pass_aes_iv), "\t\0 ");

                /**
                 *   Clean all not pritnable --- 2-3 days to find out
                 *
                 */
                $_tmp = preg_replace('/[^(\x20-\x7F)]*/', '', $_tmp);
                $_ret = htmlentities($_tmp, ENT_QUOTES, 'UTF-8');

               // trigger_error(" Decrypted from AES is [$_ret] " . htmlspecialchars_decode($_ret) . " len =" . strlen($_ret));
            }

            return ($_ret);
        }

    }