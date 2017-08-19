<?php


    require_once 'Zend/Search/Lucene.php';
    require_once 'config.php';



    // Optimize
    // Open existing index
    $index = Zend_Search_Lucene::open(LUCENE_INDEX_PATH);

    // Optimize index.
    $index->optimize();

