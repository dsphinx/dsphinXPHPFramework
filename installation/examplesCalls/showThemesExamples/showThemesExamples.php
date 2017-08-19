<?php
    /**
     *    Testing Framework Plugins -  Feb 2014
     *
     */

    Init::_load('Unix');

    $_tmp  = Unixfile::get_files_from_dir(__DIR__);
    $files = array();
    $cx    = 0;
    foreach ($_tmp as $f) {
        $t                  = explode('.', $f);
        $name               = $t[0];
        $files[$cx]['name'] = '<a target="_blank" href="?page=' . $name . '">' . $name . '</a>';
        $cx++;
    }


    $_tmp = Unixfile::get_dirs_from_dir(__DIR__);
    foreach ($_tmp as $f) {
        $t    = explode('.', $f);
        $name = $t[0];
        if ($name != "") {
            $files[$cx]['name'] = '<a target="_blank" href="?page=setTheme&theme=' . $name . '"><mark> Directory </mark> >> ' . $name . '</a>';
            $cx++;
        }

    }


    $_tmp_header = array(
        'name' => 0,
    );


    array_multisort($files);

    Html_Table::show($_tmp_header, $files, "400px", "Bootstrap Themes Examples - Supported by Framewrok");

    echo ' from http://bootstrapzero.com/ ';