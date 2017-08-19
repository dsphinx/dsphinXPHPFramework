<?php
    /**
     * difF PHP Framework :: SessionsSecure.php
     *
     * @version: 2
     * @date   : Sept 2013
     * @author Melisides Constantinos (dsphinx@gmail.com)
     * @author http://stephenmcintyre.net/blog/custom-php-session-class
     * Session Handling Functions using MySQL.
     * simply include() this file at the top of any script you wish to use Sessions in.
     * As long as the table exists in the database, all Sessions will be stored in that table.
     * This file can be places onto multiple webservers running the same website and they will begin to share Sessions between them.

    CREATE TABLE `sessions` (
    `id` char(128) NOT NULL,
    `set_time` char(10) NOT NULL,
    `data` text NOT NULL,
    `session_key` char(128) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;


    http://www.wikihow.com/Create-a-Secure-Session-Managment-System-in-PHP-and-MySQL
     * Licensed under MIT licence:
     *   http://www.opensource.org/licenses/mit-license.php
     **/


    class Sessions
    {
        // Set to true if using https
        function __construct($session_name, $secure)
        {
            // set our custom session functions.
            session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc'));

            // This line prevents unexpected effects when using objects as save handlers.
            register_shutdown_function('session_write_close');

            $this->start_session($session_name, $secure);
        }

        // Set to true if using https
        function start_session($session_name, $secure)
        {
            // Make sure the session cookie is not accessable via javascript.
            $httponly = TRUE;

            // Hash algorithm to use for the sessionid. (use hash_algos() to get a list of available hashes.)
            $session_hash = 'sha512';

            // Check if hash is available
            if (in_array($session_hash, hash_algos())) {
                // Set the has function.
                ini_set('session.hash_function', $session_hash);
            }
            // How many bits per character of the hash.
            // The possible values are '4' (0-9, a-f), '5' (0-9, a-v), and '6' (0-9, a-z, A-Z, "-", ",").
            ini_set('session.hash_bits_per_character', 5);

            // Force the session to only use cookies, not URL variables.
            ini_set('session.use_only_cookies', 1);

            // Get session cookie parameters
            $cookieParams = session_get_cookie_params();
            // Set the parameters
            session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
            // Change the session name
            session_name($session_name);
            // Now we cat start the session
            session_start();
            // This line regenerates the session and delete the old one.
            // It also generates a new encryption key in the database.
            session_regenerate_id(TRUE);
        }


        function open()
        {
            $this->db = new MYSQLi(SQL_HOST, SQL_USER, SQL_PASSWORD, SQL_BDD); //  config.php
            return TRUE;
        }


        function close()
        {
            $this->db->close();

            return TRUE;
        }

        function read($id)
        {
            if (!isset($this->read_stmt)) {
                $this->read_stmt = $this->db->prepare("SELECT data FROM SessionsSecure WHERE id = ? LIMIT 1");
            }
            $this->read_stmt->bind_param('s', $id);
            $this->read_stmt->execute();
            $this->read_stmt->store_result();
            $this->read_stmt->bind_result($data);
            $this->read_stmt->fetch();
            $key  = $this->getkey($id);
            $data = $this->decrypt($data, $key);

            return $data;
        }


        function write($id, $data)
        {
            // Get unique key
            $key = $this->getkey($id);
            // Encrypt the data
            $data = $this->encrypt($data, $key);

            $time = time();
            if (!isset($this->w_stmt)) {
                $this->w_stmt = $this->db->prepare("REPLACE INTO SessionsSecure (id, set_time, data, session_key) VALUES (?, ?, ?, ?)");
            }

            $this->w_stmt->bind_param('siss', $id, $time, $data, $key);
            $this->w_stmt->execute();

            return TRUE;
        }


        function destroy($id)
        {
            if (!isset($this->delete_stmt)) {
                $this->delete_stmt = $this->db->prepare("DELETE FROM SessionsSecure WHERE id = ?");
            }
            $this->delete_stmt->bind_param('s', $id);
            $this->delete_stmt->execute();

            return TRUE;
        }


        function gc($max)
        {
            if (!isset($this->gc_stmt)) {
                $this->gc_stmt = $this->db->prepare("DELETE FROM SessionsSecure WHERE set_time < ?");
            }
            $old = time() - $max;
            $this->gc_stmt->bind_param('s', $old);
            $this->gc_stmt->execute();

            return TRUE;
        }


        private function getkey($id)
        {
            if (!isset($this->key_stmt)) {
                $this->key_stmt = $this->db->prepare("SELECT session_key FROM SessionsSecure WHERE id = ? LIMIT 1");
            }
            $this->key_stmt->bind_param('s', $id);
            $this->key_stmt->execute();
            $this->key_stmt->store_result();
            if ($this->key_stmt->num_rows == 1) {
                $this->key_stmt->bind_result($key);
                $this->key_stmt->fetch();

                return $key;
            } else {
                $random_key = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));

                return $random_key;
            }
        }


        private function encrypt($data, $key)
        {
            $salt      = 'cH!swe!retReGu7W6bEDRup7usuDUh9THeD2CHeGE*ewr4n39=E@rAsp7c-Ph@pH';
            $key       = substr(hash('sha256', $salt . $key . $salt), 0, 32);
            $iv_size   = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv        = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB, $iv));

            return $encrypted;
        }


        private function decrypt($data, $key)
        {
            $salt      = 'cH!swe!retReGu7W6bEDRup7usuDUh9THeD2CHeGE*ewr4n39=E@rAsp7c-Ph@pH';
            $key       = substr(hash('sha256', $salt . $key . $salt), 0, 32);
            $iv_size   = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv        = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($data), MCRYPT_MODE_ECB, $iv);

            return $decrypted;
        }
    }


    new Sessions('_s', FALSE);
// Set to true if using https
//$session->start_session


