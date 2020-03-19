<?php

    use DynamicalWeb\Actions;

    if(file_exists("/etc/acm/maintenance.bin"))
    {
        Actions::redirect("blackhole.intellivoid.net");
        exit(0);
    }