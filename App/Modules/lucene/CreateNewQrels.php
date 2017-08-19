<?php


    require_once 'Zend/Search/Lucene.php';
    require_once 'config.php';


    $qrel_orig = file_get_contents('/data_1TB/storage/lucene/bin/Original txt/MiniCollectionQrelsTest.txt');
    $qrel_terrier  = file_get_contents('/data_1TB/storage/lucene/bin/OK terrier_files/qrels.qrels');


    echo "Creating new qrels  from terrier qrels  ... \n";
    $res = fopen(LUCENE_RESULTS_QRELS, "w+");

    $terrier = explode("\r\n",$qrel_terrier);
    $qrel = explode("\r\n",$qrel_orig);


    echo "\n total qresl ".count($terrier);
    echo "\n total qresl ".count($qrel);

    $cx = 0;
    foreach ($terrier as $l) {

        $ar = explode(' ',$l);
        $tt = explode(' ',$qrel[$cx]);
        $ttt = $tt[0];
        //$tt = explode(' ', trim($ttt));
        $tt = preg_split ('/[,\s]+/', $ttt);

        // echo $tt[1];
       // $ttt = explode(' ',$tt);
        //$ar = preg_split('/\s*,\s*/', trim($l));
        //$tt = prg_split('/\s*,\s*/', trim($qrel[$cx]));
     //   echo "[$ttt]  \n";
        $string = $ar[0]. " 0 ". $tt[1]." " .$ar[3]. "\n";

     //   $string = "$cx Q0 $fileis $relevant $scoreis VSM_Lucene \n";
        if (!fwrite($res, $string)) {
            echo ' error writing res ';
       }
        echo $string;

        $cx++;
    }



/*
    foreach ($qrel as $l) {
        echo "\n -- ".$l;

    }
*/

    fclose($res);
echo " new qrels ".LUCENE_RESULTS_QRELS;