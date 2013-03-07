<?php

return array(
    "name"=> "atelierspierrot/webfilesystem",
    "type"=> "library",
    "version"=>"0.0.1",
    "description"=> "Extending the SPL file system to manage webserver based file system (such as assets).",
    "keywords"=> array(
        "file", "directory", "webserver", "spl"
    ),
    "homepage"=> "http=>//webfilesystem.ateliers-pierrot.fr",
    "license"=> "GPL-3.0",
    "authors"=> array(
        array("name"=> "Pierre Cassat", "email"=> "piero.wbmstr@gmail.com")
    ),
    "require"=> array(
        "php"=> ">=5.3.0"
    ),
    "autoload"=> array(
        "psr-0"=> array( "WebFilesystem"=> "src/" )
    )
);

