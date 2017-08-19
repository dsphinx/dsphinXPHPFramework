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
     * Zend_Search_Lucene component works with Java Lucene 1.4-1.9, 2.1 and 2.3 index formats.

            http://en.wikipedia.org/wiki/Vector_space_model
     */

    DEFINE ( "LUCENE_INDEX_PATH", __DIR__. '/index' );  // Optimized   --- OptimizeIndex.php




//    DEFINE ( "LUCENE_TOPICS", '/data/storage/MSc/IR/code/OK terrier_files/Trec_Topics_Test_Without_DESC.xml' ); // Topics
    DEFINE ( "LUCENE_TOPICS", '/data/storage/MSc/IR/code/Data/TrecTopics.xml' ); // Topics



    DEFINE ( "LUCENE_RESULTS", '/data/storage/MSc/IR/code/Data_Runs/lucene.res' ); //  Results File
    DEFINE ( "LUCENE_RESULTS_TOPICS", LUCENE_RESULTS . '.newTOPICS' );  //  new topics file με ννέο tag
    DEFINE ( "LUCENE_RESULTS_QRELS", '/data/storage/MSc/IR/code/Data/lucene.qrels' ); //  Results File


  //  DEFINE ( "STOPWORDS", '/data_1TB/storage/lucene/terrier-3.6/share/stopword-list.txt');
    DEFINE ( "STOPWORDS", __DIR__ . '/Data/stopword-list.txt');


    DEFINE ( "RECREATE_INDEX", TRUE );
    DEFINE ( "MAX_RESULTS", 1000 );



    // Long Live Zend
    require_once 'Zend/Search/Lucene.php';
    require_once 'Zend/Search/Lucene/Analysis/TokenFilter.php';
    require_once 'Zend/Search/Lucene/Analysis/TokenFilter/StopWords.php';
    require_once 'Zend/Filter/Alnum.php';

    require_once 'class.stemmer.inc';           // Our Port Stemmer



    static $TOTAL = 0 ;


        /*
         *
         * http://wiki.apache.org/lucene-java/InformationRetrieval
         *
         * http://framework.zend.com/apidoc/1.9/Zend_Search_Lucene/Document/Zend_Search_Lucene_Document.html#sec-var-summary
         * http://framework.zend.com/manual/1.12/en/zend.search.lucene.index-creation.html
         *
         * http://lucene.apache.org/core/3_6_2/scoring.html
         *
         * Lucene scoring uses a combination of the Vector Space Model (VSM) of Information Retrieval and the Boolean model to determine how relevant a
         * given Document is to a User's query. In general, the idea behind the VSM is the more times a query term appears in a document relative to the
         * number of times the term appears in all the documents in the collection, the more relevant that document is to the query. It uses the
         * Boolean model to first narrow down the documents that need to be scored based on the use of boolean logic in the Query specification.
         * Lucene also adds some capabilities and refinements onto this model to support boolean and fuzzy searching, but it essentially remains a
         * VSM based system at the heart. For some valuable references on VSM and IR in general refer to the Lucene Wiki IR references.


            STOP WORDS
            http://doczf.mikaelkael.fr/1.0/en/zend.search.lucene.extending.html

            http://framework.zend.com/manual/1.11/en/zend.filter.set.html

            http://robertelwell.info/blog/zend_search_lucene-tips/
         */



    /*
     *  Recursive Remove Directory
     *
     */
    function Recursive_rmdir ($path)
    {
        $i = new DirectoryIterator( $path );
        foreach ($i as $f) {
            if ($f->isFile ()) {
                unlink ($f->getRealPath ());
            } else if (! $f->isDot () && $f->isDir ()) {
                Recursive_rmdir ($f->getRealPath ());
            }
        }
        rmdir ($path);
        return TRUE;
    }



    /*
     *   Get Tag Text , not only HTML
     */
    function getTextTags($string, $tagname)
    {
        $pattern = "/<$tagname ?.*>([\w\W]*?)<\/$tagname>/";
        preg_match($pattern, $string, $matches);



        return $matches[1];
    }

    /*
   *   Get Tag Text , not only HTML
     * /data_1TB/storage/lucene/Patents_TREC/4/EP-0614076.txt 29
   */
    function getTextTagsAll($string, $tagname)
    {
        $pattern = "/<$tagname ?.*>([\w\W]*?)<\/$tagname>/";
        preg_match($pattern, $string, $matches);

        $txt =strip_tags( $matches[1]);
        $txt = trim(preg_replace('/\s\s+/', ' ,', $txt));

    //    echo $txt;
      //  $matches = explode()
        // echo $matches[1];
      //  die();
      //  var_dump($matches);

    //    echo "- $l \n";
        return $txt;
    }



    function getTags( $what ) {

        $tags = array(
            'no' => 'DOCNO',
            'title' => 'INVENTION-TITLE',
            'class' => 'CLASSIFICATIONS',
            'class1' => 'CLASSIFICATIONS-THIRD',
            'class2' => 'CLASSIFICATIONS-FOURTH',
            'abstract' => 'ABSTRACT',
            'applicant' => 'APPLICANT-NAME',
            'inventor' => 'INVENTOR-NAME',
            'description' => 'DESCRIPTION',
            'claims'    => 'CLAIMS'
        );


        return ($tags[$what] ? $tags[$what] : '');
    }


    function addToIndex ($index, $path)
    {
        $path = new DirectoryIterator( $path );
        foreach ($path as $f) {
            if ($f->isFile ()) {
                $file = $f->getRealPath ();
                $item = file_get_contents($file);

                $docno = getTextTags($item, getTags('no'));
                $title = getTextTags($item, getTags('title'));

                if (is_null($docno) || is_null($title)) {
                    echo "\t *** Empty DOCNO και τίτλος ";
                }


                echo "Adding  $file id =  $docno , $title";

                $doc = new Zend_Search_Lucene_Document();

                $doc->addField (Zend_Search_Lucene_Field::Text ('no',    $docno));
                $doc->addField (Zend_Search_Lucene_Field::Text ('link',    $file));
                $doc->addField (Zend_Search_Lucene_Field::Text ('title',    $title));
                $doc->addField (Zend_Search_Lucene_Field::Text ('abstract', getTextTags($item, getTags('abstract')) ));
                $doc->addField (Zend_Search_Lucene_Field::Text ('contents', getTextTags($item, getTags('description')) ));
                $doc->addField (Zend_Search_Lucene_Field::Text ('class', getTextTags($item, getTags('class')) ));
                $doc->addField (Zend_Search_Lucene_Field::Text ('class1', getTextTags($item, getTags('class1')) ));
                $doc->addField (Zend_Search_Lucene_Field::Text ('class2', getTextTags($item, getTags('class2')) ));

                $classes = getTextTags($item, getTags('class')) . ' '. getTextTags($item, getTags('class1')). ' '.getTextTags($item, getTags('class2'));



              //  echo getTextTagsAll($item, 'INVENTORS');
            //    echo getTextTagsAll($item, 'APPLICANTS');
                //    continue;

                $doc->addField (Zend_Search_Lucene_Field::Text ('inventor', getTextTags($item, getTags('inventor')) ));

                $doc->addField (Zend_Search_Lucene_Field::Text ('claims', getTextTags($item, getTags('claims')) ));
                $doc->addField (Zend_Search_Lucene_Field::Text ('applicant', getTextTags($item, getTags('applicant')) ));


                // Extra πεδια για παραπάνω βαρύτητα

                $doc->addField (Zend_Search_Lucene_Field::Text ('applicantALL', getTextTagsAll($item, 'APPLICANTS' ) ));
                $doc->addField (Zend_Search_Lucene_Field::Text ('classes_all', $classes ));     // Βαρύτητα
                $doc->addField (Zend_Search_Lucene_Field::Text ('inventorALL', getTextTagsAll($item, 'INVENTORS' )));

                $index->addDocument ($doc);
                $TOTAL++;
                echo "done patent # $TOTAL\n";


            } else if (! $f->isDot () && $f->isDir () ) {
                AddToIndex ($index, $f->getRealPath ());
            }
        }
    }


    // recursive
    function array_key_exists_r($needle, $haystack)
    {
        $result = array_key_exists($needle, $haystack);
        if ($result)
            return $result;
        foreach ($haystack as $v)
        {
            if (is_array($v) || is_object($v))
                $result = array_key_exists_r($needle, $v);
            if ($result)
                return $result;
        }
        return $result;
    }
