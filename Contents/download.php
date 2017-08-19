<?php

    set_time_limit(0);

    include '../App/Ajax/downloadManager.php';


    $downloadPath        = isset($_GET['path']) ? $_GET['path'] : "uploads/";
    $downloadFile        = isset($_GET['md5']) ? $_GET['md5'] : NULL;
    $downloadFilenameOut = isset($_GET['name']) ? $_GET['name'] : NULL;

  //  DEFINE ("DOWNLOAD_PATH", __DIR__ . '/../Media/' . $downloadPath);
    DEFINE ("DOWNLOAD_PATH", __DIR__ . '/../../../www/Media/' . $downloadPath);


    output_file($downloadFile, $downloadFilenameOut);
    output_file_log($downloadFilenameOut);
