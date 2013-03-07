<?php

namespace DemoBuilder;

use \FileSystem\ConfigFile,
    \FileSystem\DemoPageDirectory;

interface DemoPageInterface
{

    /**
     * Set the configuration file entity
     */
    public function setConfigFile(ConfigFile $file);

    /**
     * Get the configuration file entity
     */
    public function getConfigFile();

    /**
     * Set the demo directory entity
     */
    public function setDemoPageDirectory(DemoPageDirectory $directory);

    /**
     * Get the demo directory entity
     */
    public function getDemoPageDirectory();

}

// Endfile