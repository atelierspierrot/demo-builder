<?php

namespace DemoBuilder;

use \FileSystem\PackageManifest,
    \FileSystem\DemoManifest,
    \FileSystem\DemoDirectory;

use \DemoBuilder\Page;

interface DemoBuilderInterface
{

    /**
     * Is the demo configured enough to build pages ?
     */
    public function isConfigured();

    /**
     * Set the package entity
     */
    public function setPackage(PackageManifest $manifest);

    /**
     * Get the package entity
     */
    public function getPackage();

    /**
     * Get a configuration value from the package
     */
    public function getPackageValue($name);

    /**
     * Set the demo entity
     */
    public function setDemo(DemoManifest $manifest);

    /**
     * Get the demo entity
     */
    public function getDemo();

    /**
     * Get a configuration value from the demo
     */
    public function getDemoValue($name);

    /**
     * Set the pages directory entity
     */
    public function setPagesDirectory(DemoDirectory $directory);

    /**
     * Get the pages directory entity
     */
    public function getPagesDirectory();

    /**
     * Get a configuration value from the pages directory
     */
    public function getPagesDirectoryValue($name);

    /**
     * Set the current page entity
     */
    public function setPage(Page $page);

    /**
     * Get the current page entity
     */
    public function getPage();

    /**
     * Get a configuration value from the current page
     */
    public function getPageValue($name);

    /**
     * Get a configuration value from the current page or the whole demo configuration
     */
    public function getPageOrDemoValue($name);

    /**
     * Get a configuration value from the demo or the package
     */
    public function getDemoOrPackageValue($name);

    /**
     * Special system locators : must return the found path or throw an exception
     */
    public function locate($path);
    public function locateDirectory($path);
    public function locateFile($path);

}

// Endfile