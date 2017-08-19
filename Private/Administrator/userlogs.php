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
     */


    $showpage            = isset($_GET["pg"]) ? Controller::param_integer("pg") : 1;
    $_link_pagination    = "login.php?Administrator&cmd=userlogs";
    $wheresql            = NULL;
    $sql_params          = NULL;
    $_link_pagination .= "&pg=";


    $db = MyDB::db();

    $sql     = "SELECT *,(SELECT login FROM Auth Where Auth.id=Auth_id )as name FROM AuthLogging order by date desc limit 0,50";
    $logging = $db->sql($sql);



    $_show_files = array(
        "id"      => 30,
        "ip" => 110,
        "agents" => 700,
        "Date"    => 200,
        "userID"      => 12,
        "geo"      => 12,
        "user"      => 220,
    );



    echo Html::javascript('jquery.tablesorter.min.js');

    Html_Table::show($_show_files, $logging, "1100px", "Authenticated Users history");
    echo Html_Table::init_tablesorter();
    echo $navigation;



