<?php
    /**
     *  Copyright (c) 16/11/14 , dsphinx@plug.gr
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
     *  Created : 7:23 PM - 16/11/14
     *
     */


    class SFTPConnection
    {
        private $connection;
        private $sftp;
        const    publicKeyPhraseSecret = "Xxx";

        public function __construct($host, $port = 22, $_PUBLIC_KEY_USER = FALSE)
        {

            if ($_PUBLIC_KEY_USER) {
                $this->connection = @ssh2_connect($host, $port, array('hostkey' => 'ssh-rsa'));
                ssh2_auth_pubkey_file($this->connection, $_PUBLIC_KEY_USER,
                    '/home/' . $_PUBLIC_KEY_USER . '/.ssh/id_rsa.pub',
                    '/home/' . $_PUBLIC_KEY_USER . '/.ssh/id_rsa', self::publicKeyPhraseSecret); // δε θυμάμει το secret ...
            } else {
                $this->connection = @ssh2_connect($host, $port);
            }

            if (!$this->connection)
                throw new Exception("Could not connect to $host on port $port.");
        }


        public function login($username, $password)
        {
            if (!@ssh2_auth_password($this->connection, $username, $password))
                throw new Exception("Could not authenticate with username $username " .
                    "and password $password.");

            $this->sftp = @ssh2_sftp($this->connection);
            if (!$this->sftp)
                throw new Exception("Could not initialize SFTP subsystem.");
        }


        public function put($local_file, $remote_file)
        {
            $sftp   = $this->sftp;
            $stream = @fopen("ssh2.sftp://$sftp$remote_file", 'w');

            if (!$stream)
                throw new Exception("Could not open file: $remote_file");

            $data_to_send = @file_get_contents($local_file);
            if ($data_to_send === FALSE)
                throw new Exception("Could not open local file: $local_file.");

            if (@fwrite($stream, $data_to_send) === FALSE)
                throw new Exception("Could not send data from file: $local_file.");

            @fclose($stream);
        }


        function scanFilesystem($remote_file)
        {
            $sftp      = $this->sftp;
            $dir       = "ssh2.sftp://$sftp$remote_file";
            $tempArray = array();
            $handle    = opendir($dir);
            // List all the files
            while (FALSE !== ($file = readdir($handle))) {
                if (substr("$file", 0, 1) != ".") {
                    if (is_dir($file)) {
                        //                $tempArray[$file] = $this->scanFilesystem("$dir/$file");
                    } else {
                        $tempArray[] = $file;
                    }
                }
            }
            closedir($handle);

            return $tempArray;
        }


        public function get($remote_file, $local_file)
        {
            $sftp   = $this->sftp;
            $stream = @fopen("ssh2.sftp://$sftp$remote_file", 'r');
            if (!$stream)
                throw new Exception("Could not open remote file: $remote_file, real: $sftp$remote_file");
            $size     = $this->getFileSize($remote_file);
            $contents = '';
            $read     = 0;
            $len      = $size;
            while ($read < $len && ($buf = fread($stream, $len - $read))) {
                $read += strlen($buf);
                $contents .= $buf;
            }
            file_put_contents($local_file, $contents);
            @fclose($stream);
        }


        public function getFileSize($file)
        {
            $sftp = $this->sftp;

            return filesize("ssh2.sftp://$sftp$file");
        }
    }
