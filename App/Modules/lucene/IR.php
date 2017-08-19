<?php


    require_once 'config.php';




 //   die(" Έγινε ήδη ο index, πήρε χρόνο να μην ξανατρεξω , optimized\n");
  //  die();

    if (RECREATE_INDEX) {
        echo ' Removing old index directory  ' . LUCENE_INDEX_PATH . " \n";
        if (! Recursive_rmdir (LUCENE_INDEX_PATH)) {
            echo ' Error removing index dir ';
            die();
        }
    }


    error_reporting (NULL);


    function sanitize ($input)
    {
        return htmlentities (strip_tags ($input));
    }

    /*
     *   Creating Index
     */
    $index = new Zend_Search_Lucene( LUCENE_INDEX_PATH, TRUE );




    addToIndex ($index, $_SESSION['PATHS']['_ROOT_'] . $_SESSION['PATHS']['FILES']['HTML']);
    addToIndex ($index, $_SESSION['PATHS']['_ROOT_'] . $_SESSION['PATHS']['FILES']['CONTENTS']);
  //  AddToIndex ($index, $_SESSION['PATHS']['_ROOT_'] . $_SESSION['PATHS']['FILES']['MEMBERS']);


    $index->commit ();

    echo $index->count () . " Documents indexed.\n";


