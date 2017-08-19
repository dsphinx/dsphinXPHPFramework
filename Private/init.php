<?php

    if (Controller::Policy(100, " diagnosis ")) {
        return;
    }

    $db          = new MyDB();
