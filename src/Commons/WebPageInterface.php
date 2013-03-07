<?php

namespace Commons;

interface WebPageInterface
{

    /**
     * Build the page menu
     */
    public function buildMenu();

    /**
     * Get the page menu
     */
    public function getMenu();

    /**
     * Build the page title
     */
    public function buildTitle();

    /**
     * Get the page title
     */
    public function getTitle();

    /**
     * Build the page metas
     */
    public function buildMetas();

    /**
     * Get the page metas
     */
    public function getMetas();

    /**
     * Get one page meta
     */
    public function getMeta($name);

}

// Endfile