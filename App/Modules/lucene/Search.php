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
     *
     *
     * http://framework.zend.com/manual/1.12/en/zend.search.lucene.searching.html
     *
     */

    require_once 'Zend/Search/Lucene.php';
    require_once 'config.php';



    $index = new Zend_Search_Lucene( LUCENE_INDEX_PATH );


    $query = NULL;

    $arg_list = count($argv);

    for ($i = 1; $i < $arg_list; $i ++) {
        $query .= $argv[ $i ] . ' ';
       // echo "Searching $i is: " . $argv[ $i ] . "<br />\n";
    }


    if ($query) {
        echo "Query  searching :  $query   \n";
    } else {
        echo "Give param to Query \n $argv[0]  <something to search>      \n";
        die();
    }

    Zend_Search_Lucene::setResultSetLimit(20);

    $hits = $index->find ($query);

    echo "Index contains " . $index->count () . " documents.\n\n";

    echo "Search for '" . $query . "' returned " . count ($hits) . " hits\n\n";

    foreach ($hits as $hit) {
        echo $hit->title . "\n";
        $scoreis = sprintf("%2.14f", $hit->score * 100);

        echo "\tScore: $scoreis " . sprintf ('%.2f', $hit->score) . "\n";
        echo "Inventors : " . $hit->inventorALL . "\n";
        echo "Classes   : " . $hit->classes_all . "\n";
        echo "Applicants: " . $hit->applicantALL . "\n";


        //   echo "Abstract" . $hit->abstract . "\n\n";
        //   echo "Abstract" . $hit->contents . "\n\n";
        echo "\t" . $hit->link . "\n ********* \n";
    }

