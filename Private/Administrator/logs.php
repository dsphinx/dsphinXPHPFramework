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


    $_config = array(
        "tablename" => "Logging",
        "fields"    => NULL,
        "criteria"  => NULL,
        "caption"   => NULL,
        "section"   => NULL,
    );

    $_config['criteria'] = Controller::param("criteria");
    $_config['fields']   = isset($_GET["fields"]) ? Controller::param("fields") : "id,section,message,date,ip";
    $_config['caption']  = isset($_GET["caption"]) ? Controller::param("caption") : "Log Viewer";
    $_config['section']  = html_entity_decode(trim(Controller::param("section")));
    $_config['id']       = isset($_GET["id"]) ? ' WHERE id=' . Controller::param_integer("id") : NULL;
    $showpage            = isset($_GET["pg"]) ? Controller::param_integer("pg") : 1;
    $_link_pagination    = "login.php?Administrator&cmd=logs";
    $wheresql            = NULL;
    $sql_params          = NULL;


    $sql = "SELECT $_config[fields] FROM `$_config[tablename]`" . $_config['id'];

    if ($_config['section']) {
        $sql_params = array($_config['section']);
        $_link_pagination .= "&section=" . $_config['section'];
        if (!$_config['id'])
            $sql .= " WHERE section=(?) ";
    }

    $sql .= " ORDER BY Date DESC ";


    $_link_pagination .= "&pg=";


    if (Controller::param("username")) { // Username
        $sql_w   = "SELECT $_config[fields]
					FROM  `$_config[tablename]`
					WHERE message LIKE ?  ORDER BY Date Desc LIMIT 0, 20";
        $param   = array("%" . Controller::param("username") . "%");
        $logging = $db->rawQuery($sql_w, $param);

    } else {

        $navigation = "";
        $logging    = $db::pagination($sql, $sql_params, $navigation, $_link_pagination, $showpage);

    }

    $_show_files = array(
        "id"      => 30,
        "Section" => 110,
        "Message" => 700,
        "Date"    => 200,
        "ip"      => 120,
    );

    $sections_are = $db->sql("SELECT DISTINCT section FROM `$_config[tablename]`");
    echo " <h5> Categories : </h5> ";

    foreach ($sections_are as $r) {
        echo '<a class="btn btn-info btn-xs "  href="' . $_link_pagination . '&pg=' . $showpage . '&section=' . $r['section'] . '">' . $r['section'] . '</a>';
    }

    echo Html::javascript('jquery.tablesorter.min.js');

    Html_Table::show($_show_files, $logging, "1180px", $_config['caption']);
    echo Html_Table::init_tablesorter();
    echo $navigation;



