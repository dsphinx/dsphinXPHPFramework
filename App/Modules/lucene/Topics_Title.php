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
     */

    require_once 'config.php';


    $index = new Zend_Search_Lucene(LUCENE_INDEX_PATH);

    echo "Index contains " . $index->count() . " documents.\n\n";

    Zend_Search_Lucene::setResultSetLimit(MAX_RESULTS);


    echo "Cleaning Results \n";
    $res = fopen(LUCENE_RESULTS, "w+");
    $res_new = fopen(LUCENE_RESULTS_TOPICS, "w+");
    echo LUCENE_RESULTS_TOPICS. " New Topics File  .. \n\n ";


    echo ' Relative Topics .. \n\n ';


    $xml = file_get_contents(LUCENE_TOPICS);


    $topics = explode("<topic>", $xml);

    $cx         = 1;
    $flag_limit = 0.03; // Stop below that


    // STOP WORDS
  // $tmp = file(STOPWORDS);
  // var_dump($tmp);
   $stopWordsFilter = new Zend_Search_Lucene_Analysis_TokenFilter_StopWords();
   $stopWordsFilter->loadFromFile(STOPWORDS);
   //echo "** loading Stopwrod ".$count;




    // Case Insesitive o
   $analyzer =   new Zend_Search_Lucene_Analysis_Analyzer_Common_TextNum_CaseInsensitive();
   $analyzer->addFilter($stopWordsFilter);

    // Shortwords ??




    // Analyzer filters !!
    //
    Zend_Search_Lucene_Analysis_Analyzer::setDefault($analyzer);



    $wordfilter = new Zend_Filter_Alnum();
    $linefilter = new Zend_Filter_Alnum(array('allowwhitespace' => true));

    $stemming = new Stemmer();

    foreach ($topics as $t) {


        $docno    = getTextTags($t, "num");// . "\n";

        if (intval($docno) ==0 )
                continue;

        $question = getTextTags($t, "title");// . "\n";
     //   $question = getTextTags($t, "narr");// . "\n";
        $abst     = getTextTags($t, "abstract");// . "\n";
        $claims   = getTextTags($t, "claims");

        /*
         *
         *
         *
         $question = getTextTags($t, "title");// . "\n";
     //   $question = getTextTags($t, "narr");// . "\n";
        $abst     = getTextTags($t, "abstract");// . "\n";
        $claims   = getTextTags($t, "claims");

         */




      //  $new_tag = " $question"; // Run 1
      //   $new_tag = " $abst"; // Run 2
     //   $new_tag = "$question"; // Run 3
        $new_tag = "$question $abst"; // Run 3

        $new_tag_topics = $linefilter->filter($new_tag);



      // νεο tag με λέξεις με μεγαλύτερο score
        echo "<tag>$new_tag_topics </tag>";
      //  var_dump($cx_words_array);



         if (!fwrite($res_new, "<topic><num>$docno</num>\n<tag>$new_tag_topics </tag>\n<title>$question</title>\n<abstract>\n$abst</abstract>\n<claims>\n$claims</claims>\n</topic>")) {
             echo ' error writing res ';
         }

     //   die();


       $newtag_is = $new_tag_topics; // αυτό δεν θέλουμε

        echo "** Processing Topic $cx : $docno\n**\t $question \n με $newtag_is\n";

  //      continue;





     //   $hits = $index->find($question);
       $hits = $index->find ($newtag_is);

        $relevant =0 ;          // Βαθμός σχετικότητας Rank

        foreach ($hits as $hit) {
            $title = $hit->title;
            $link  = $hit->link;
            $scoreis = sprintf("%2.14f", $hit->score * 100);

            if ($flag_limit > $hit->score) {
                echo " \n Threshold , next topic \n";
                break;
            }


            if ($hit->score == 1) { // 100%
            //    $scoreis = "100.00000000000000";

                echo "\t $scoreis % Relevant \n\t $title";
                echo "\n\t Link : $link \n";

                /*  Terrier output

                1 Q0 1029674 0 62.7615090651867 InL2c1.0
                1 Q0 0622209 1 59.28851022319269 InL2c1.0
                */


            }


            /*
             *
             *   Στην ουσία αυτό πρέπει να βρούμε πως θα γίνει το output arxeio .res
             *    να τροποποιήοσυμε κατάλληλα το $string
             */
            $fileis = basename($link);
            $fileis = preg_replace("/\\.[^.\\s]{3,4}$/", "", $fileis);

            $string = "$cx Q0 $fileis $relevant $scoreis VSM_Lucene \n";
            if (!fwrite($res, $string)) {
                echo ' error writing res ';
            }
            $relevant++;



            if ($relevant > MAX_RESULTS) {
                break;
            }

            echo "$link , score =" . $hit->score . "\n";
        }


        $cx++;
    }


    fclose($res);
    fclose($res_new);

